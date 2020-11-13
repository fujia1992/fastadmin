<?php
namespace addons\webide\service;

/**
 * 项目文件管理
 * @author: vace(ocdo@qq.com)
 * @description: 新增删除，编辑、目录操作等
 */
class CFile extends CBase{

  //////////////////////////////////////////////////////////////////
  // PROPERTIES
  //////////////////////////////////////////////////////////////////

  public $rel_path      = "";
  public $path          = "";
  public $patch         = "";
  public $new_name      = "";
  public $content       = "";
  public $upload        = "";
  public $controller    = "";

  public function __construct() {
    parent::__construct();

    $get = $this->request->get();
    $post = $this->request->post();
    $path = static::cleanPath($get['path']);

    $this->rel_path = $path;

    if ($this->rel_path !== "/") {
      $this->rel_path .= "/";
    }
    // 只允许当前的项目目录
    $this->path = static::cleanPath($this->root . $path);
    // Modify\Create
    if (!empty($get['new_name'])) {
      $this->new_name = $get['new_name'];
    }

    foreach (array('content', 'mtime', 'patch') as $key) {
      if (!empty($post[$key])) {
        if (get_magic_quotes_gpc()) {
            $this->$key = stripslashes($post[$key]);
        } else {
            $this->$key = $post[$key];
        }
      }
    }
    // dump($this);die;
  }

  /**
   * [index 获取文件索引]
   * @return [type] [description]
   */
  public function index() {
    $this->_condition(!file_exists($this->path), 'Path Does Not Exist', $this->path);
    $this->_condition(!is_dir($this->path), 'Not A Directory');
    $handle = opendir($this->path);
    $this->_condition(!$handle, 'Open Directory Error');
    $folders = $files = [];
    while (false !== ($object = readdir($handle))) {
      if ($object === '.' || $object === '..' || $object === $this->controller) {
        continue;
      }
      if (is_dir($this->path . '/' . $object)) {
        $type = "directory";
        $size = 0; //count(glob($this->path.'/'.$object.'/*'));
      } else {
        $type = "file";
        $size = @filesize($this->path . '/' . $object);
      }
      $name = $this->rel_path . $object;
      $indexValue = compact('name', 'type', 'size');
      if ($type === 'directory') {
        $folders[] = $indexValue;
      } else if ($type === 'file') {
        $files[] = $indexValue;
      }
    }
    // 过滤文件夹
    $ignoreList = CSetting::$projectIgnorePath;
    if (!empty($ignoreList)) {
      $folders = array_filter($folders, function ($folder) use ($ignoreList) {
        foreach ($ignoreList as $ignore) {
          if (0 === strpos($folder['name'], $ignore)) {
            return false;
          }
        }
        return true;
      });
    }
    // 过滤文件后缀
    $ignoreExt = CSetting::$projectIgnoreFileExt;
    if (!empty($ignoreExt)) {
      $files = array_filter($files, function ($file) use ($ignoreExt) {
        return !in_array(pathinfo($file['name'], PATHINFO_EXTENSION), $ignoreExt);
      });
    }
    // 排序
    $sorter = function ($a, $b, $key = 'name') {
      return strnatcmp($a[$key], $b[$key]);
    };

    usort($folders, $sorter);
    usort($files, $sorter);

    $output = array_merge($folders, $files);
    return $this->success(['index' => $output]);
  }

  /**
   * [find 查找文件]
   * 速度太慢，暂时取消此功能
   * @return [type] [description]
   */
  public function find() {
    $this->_condition(!function_exists('shell_exec'), 'Shell_exec() Command Not Enabled.');
    chdir($this->path);
    $input = str_replace('"', '', escapeshellarg(input('query')));
    $foptions = input('options');
    $vinput = preg_quote($input);
    $cmd = 'find -L ';
    
    $strategy = isset($foptions['strategy']) ? $foptions['strategy'] : 'default';

    if ($strategy === 'left_prefix') {
      $cmd = "$cmd -iname \"$vinput*\"";
    } else if ($strategy === 'substring') {
      $cmd = "$cmd -iname \"*$vinput*\"";
    } else if ($strategy === 'regexp') {
      $cmd = "$cmd -regex \"$input\"";
    } else {
      $cmd = 'find -L -iname "' . $input . '*"';
    }

    $cmd = "$cmd  -printf \"%h/%f %y\n\"";
    $this->error($cmd);
    $output = shell_exec($cmd);
    $file_arr = explode("\n", $output);
    $output_arr = array();
    foreach ($file_arr as $i => $fentry) {
      $farr = explode(" ", $fentry);
      $fname = trim($farr[0]);
      $ftype = $farr[1] === 'f' ? 'file' : 'directory';
      if ($fname) {
        $fname = $this->rel_path . substr($fname, 2);
        $output_arr[] = ['path' => $fname, 'type' => $ftype];
      }
    }
    $this->_condition(!count($output_arr), 'No Results Returned');
    $this->success(['index' => $output_arr]);
  }

  /**
   * [search 文件搜索]
   * @return [type] [description]
   */
  public function search() {
    $this->_condition(!function_exists('shell_exec'), 'Shell_exec() Command Not Enabled.');

    $searchString = escapeshellarg(input('search_string'));
    $fileType = input('search_file_type');

    $input = preg_quote(str_replace('"', '', $searchString));
    $command = 'find -L ' . $this->path . ' -iregex  ".*' . $fileType  . '" -type f | xargs grep -i -I -n -R -H "' . $input . '"';

    $output = shell_exec($command);
    $output_arr = explode("\n", $output);
    $return = [];
    foreach ($output_arr as $line) {
      $data = explode(":", $line);
      $da = array();
      if (count($data) > 2) {
          $da['line'] = $data[1];
          $da['file'] = str_replace($this->path, '', $data[0]);
          $da['result'] = str_replace($this->root, '', $data[0]);
          $da['string'] = str_replace($data[0] . ":" . $data[1] . ':', '', $line);
          $return[] = $da;
      }
    }
    $this->_condition(!count($return), 'No Results Returned');
    $this->success(['index' => $return]);
  }

  /**
   * [open 打开文件]
   * @return [type] [description]
   */
  public function open() {
    $this->_condition(!is_file($this->path), __('Not A File :') . $this->path);
    $output = file_get_contents($this->path);
    if (extension_loaded('mbstring')) {
      if (!mb_check_encoding($output, 'UTF-8')) {
        if (mb_check_encoding($output, 'ISO-8859-1')) {
          $output = utf8_encode($output);
        } else {
          $output = mb_convert_encoding($content, 'UTF-8');
        }
      }
    }
    return $this->success([
      'content' => $output,
      'mtime'   => filemtime($this->path)
    ]);
  }

  //////////////////////////////////////////////////////////////////
  // OPEN IN BROWSER (Return URL)
  //////////////////////////////////////////////////////////////////

  public function openinbrowser() {
    $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
    $this->_condition(0 !== strpos($this->path, $DOCUMENT_ROOT), 'Not A Public Resource');
    $this->success(['url' => str_replace($DOCUMENT_ROOT, '', $this->path) ]);
  }

  /**
   * [create 创建新文件]
   * @return [type] [description]
   */
  public function create() {
    $type = $this->request->param('type');
    if ($type === 'file') {
      $this->_condition(file_exists($this->path), __('File Already Exists'));
      $file = @fopen($this->path, 'w');
      $this->_condition(!$file, __('Cannot Create File'));
      if ($this->content) {
        @fwrite($file, $this->content);
      }
      fclose($file);
      $this->success(['mtime' => filemtime($this->path)]);
    } else if ($type === 'directory'){
      $this->_condition(is_dir($this->path), __('Directory Already Exists'));
      $this->_condition(!mkdir($this->path), __('Directory Create Error'));
      $this->success();
    }
  }

  /**
   * [delete 删除文件或文件夹]
   * @return [type] [description]
   */
  public function delete() {
    $this->_condition(!file_exists($this->path), 'Path Does Not Exist');
    $this->_deleteDir($this->path, input('?follow'));
    $this->success();
  }

  /**
   * [_deleteDir 递归删除文件或者文件夹]
   * @param  [type] $path   [description]
   * @param  [type] $follow [description]
   * @return [type]         [description]
   */
  protected function _deleteDir ($path, $follow) {
    if (is_file($path)) {
      unlink($path);
    } else {
      $files = array_diff(scandir($path), array('.','..'));
      foreach ($files as $file) {
        $filename = $path . '/' . $file;
        if (is_link($filename)) {
          if ($follow) {
            $this->_deleteDir($filename, $follow);
          }
          unlink($filename);
        } elseif (is_dir($filename)) {
          $this->_deleteDir($filename, $follow);
        } else {
          unlink($filename);
        }
      }
      return rmdir($path);
    }
  }

  
  /**
   * [_modifyRename 重命名]
   * @return [type] [description]
   */
  protected function _modifyRename () {
    $explode = explode('/', $this->path);
    array_pop($explode);
    $new_path = implode("/", $explode) . "/" . $this->new_name;
    $this->_condition(file_exists($new_path), 'Path Already Exists');
    $this->_condition(!rename($this->path, $new_path), 'Could Not Rename');
    return $this->success();
  }
  /**
   * [_modifyContent 内容改变]
   * @return [type] [description]
   */
  protected function _modifyContent () {
    $this->content = trim($this->content);
    $this->_condition($this->patch && ! $this->mtime, 'mtime parameter not found');
    $this->_condition(!is_file($this->path), 'Not A File');

    $serverMTime = filemtime($this->path);
    $fileContents = file_get_contents($this->path);

    $this->_condition($this->patch && $this->mtime != $serverMTime, 'Client is out of sync');
    
    if (!trim($this->patch) && !$this->content) {
      // Do nothing if the patch is empty and there is no content
      return $this->success(['mtime' => $serverMTime]);
    }
    $file = @fopen($this->path, 'w');
    $this->_condition(!$file, 'Could Not Open File');

    if ($this->patch) {
      $diff = new DiffMatchPatch();
      $p = $diff->patch_apply($diff->patch_fromText($this->patch), $fileContents);
      $this->content = $p[0];
    }
    $this->_condition(@fwrite($file, $this->content) === false, 'Could Not Write To File');
    // Unless stat cache is cleared the pre-cached mtime will be
    // returned instead of new modification time after editing
    // the file.
    clearstatcache();
    $this->success(['mtime' => filemtime($this->path)]);
  }

  /**
   * [modify 文件改变]
   * @return [type] [description]
   */
  public function modify () {
    if ($this->new_name) {
      return $this->_modifyRename();
    }
    if ($this->content || $this->patch) {
      return $this->_modifyContent();
    }
    return $this->success(['mtime' => filemtime($this->path)]);
  }

  /**
   * [duplicate 复制文件]
   * @return [type] [description]
   */
  public function duplicate() {
    $destination = input('destination');
    $this->_condition(!file_exists($this->path), 'Invalid Source');
    $destination = static::cleanPath($this->root . $destination);

    if (is_file($this->path)) {
      $this->_condition(!copy($this->path, $destination), 'Copy Fail');
    } else if (is_dir($this->path)) {
      $this->_resourceCopy($this->path, $destination);
    } else {
      $this->error(__('Unkown Command'));
    }
    $this->success();
  }

  /**
   * [_resourceCopy 深拷贝文件]
   * @param  [type] $src [description]
   * @param  [type] $dst [description]
   * @return [type]      [description]
   */
  protected function _resourceCopy ($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
      if (( $file === '.' ) || ( $file === '..' )) {
        continue;
      }
      if (is_dir($src . '/' . $file)) {
        $this->_resourceCopy($src . '/' . $file, $dst . '/' . $file);
      } else {
        copy($src . '/' . $file, $dst . '/' . $file);
      }
    }
    closedir($dir);
  }

  /**
   * [upload 文件上传]
   * @return [type] [description]
   */
  public function upload() {
    $this->_condition(is_file($this->path), 'Not A Directory');
    // Handle upload
    $uploaded = array();

    foreach ($_FILES['upload']['name'] as $key => $filename) {
      $add = $this->path . '/' . $filename;
      if (@move_uploaded_file($_FILES['upload']['tmp_name'][$key], $add)) {
        $uploaded[] = array(
          "name"  => $filename,
          "size"  =>filesize($add),
          "url"   =>$add,
          "thumbnail_url" => $add,
          "delete_url" => $add,
          "delete_type" => "DELETE"
        );
      }
    }
    return $uploaded;
  }

}
