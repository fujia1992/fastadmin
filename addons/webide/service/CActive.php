<?php
namespace addons\webide\service;

/**
 * 用户操作文件管理
 * @author: vace(ocdo@qq.com)
 * @description: 打开文件列表等操作
 */
class CActive extends CBase {
  protected $actives;

  protected $dirty = false;

  protected function _initialize () {
    $this->actives = $this->cache('actives') ? : [];
  }

  /**
   * [_findIndex 查询文件索引]
   * @param  [type] $path [description]
   * @return [type]       [description]
   */
  protected function _findIndex ($path = NULL) {
    if (is_null($path)) {
      $path = $this->path;
    }
    foreach ($this->actives as $key => $item) {
      if ($item['path'] === $path) {
        return $key;
      }
    }
    return -1;
  }

  /**
   * [_save 保存缓存数据]
   * @param  boolean $force [强制保存]
   * @return [type]         [description]
   */
  protected function _save ($force = false) {
    if ($force || $this->dirty) {
      $this->dirty = false;
      $this->cache('actives', $this->actives);      
    }
  }

  /**
   * [list 获取文件列表，删除不存在的文件]
   * @return [type] [description]
   */
  public function list () {
    $hasFoucs = false;
    foreach ($this->actives as $key => &$item) {
      if ($item['focused']) {
        $hasFoucs = true;
      }
      if (!isset($item['path']) || !file_exists($this->root . $item['path'])) {
        $this->dirty = true;
        unset($this->actives[$key]);
      }
    }
    // 无选中项目，自动选中最后一项
    if (!$hasFoucs && isset($item)) {
      $item['focused'] = true;
    }
    $this->_save();
    return $this->success($this->actives);
  }

  /**
   * [add 添加文件]
   */
  public function add () {
    if (-1 === $this->_findIndex()) {
      $this->actives[] = ['path' => $this->path, 'focused' => false];
      $this->_save(true);
      return $this->success(['count' => count($this->actives)]);
    }
    return $this->error('Already open');
  }

  /**
   * [focused 文件获得焦点]
   * @return [type] [description]
   */
  public function focused () {
    $index = $this->_findIndex();
    if ($index !== -1) {
      foreach ($this->actives as &$value) {
        if ($value['focused']) {
          $value['focused'] = false;
        }
      }
      $this->actives[$index]['focused'] = true;
      $this->_save(true);
    }
    $this->success(['hit' => $index]);
  }

  /**
   * [remove 移除文件]
   * @return [type] [description]
   */
  public function remove () {
    $index = $this->_findIndex();
    if (-1 !== $index) {
      unset($this->actives[$index]);
      $this->_save(true);
    }
    $this->success(['hit' => $index]);
  }

  /**
   * [removeall 移除全部]
   * @return [type] [description]
   */
  public function removeall () {
    $this->actives = [];
    $this->_save(true);
    $this->success();
  }

  // TODO 检查文件权限
  public function check () {
    return $this->success('check');
  }

  public function rename () {
    $old_path = input('old_path');
    $new_path = input('new_path');
    foreach ($this->actives as &$active) {
      $active['path'] = str_replace($old_path, $new_path, $active['path']);
    }
    $this->dirty = true;
    $this->list();
  }
}
