<?php
namespace addons\webide\service;

/**
 * 编辑器后缀管理
 * @author: vace(ocdo@qq.com)
 * @description: 常用文件后缀管理
 */
class CExt extends CBase {

  /**
   * [$defaultExtensions 默认的关联后缀]
   * @var array
   */
  protected $defaultExtensions = [
    'html'   => 'html',
    'htm'    => 'html',
    'vue'    => 'html',
    'mst'    => 'html',
    'tpl'    => 'html',
    'js'     => 'javascript',
    'jsonp'  => 'javascript',
    'css'    => 'css',
    'scss'   => 'scss',
    'sass'   => 'scss',
    'less'   => 'less',
    'php'    => 'php',
    'php4'   => 'php',
    'php5'   => 'php',
    'phtml'  => 'php',
    'json'   => 'json',
    'java'   => 'java',
    'xml'    => 'xml',
    'sql'    => 'sql',
    'md'     => 'markdown',
    'c'      => 'c_cpp',
    'cpp'    => 'c_cpp',
    'd'      => 'd',
    'h'      => 'c_cpp',
    'hpp'    => 'c_cpp',
    'py'     => 'python',
    'rb'     => 'ruby',
    'erb'    => 'html_ruby',
    'jade'   => 'jade',
    'coffee' => 'coffee',
    'vm'     => 'velocity'
  ];

  /**
   * [$availiableTextModes 支持的高亮类型]
   * @var array
   */
  protected $availiableTextModes = [
    'abap',
    'abc',
    'actionscript',
    'ada',
    'apache_conf',
    'applescript',
    'asciidoc',
    'assembly_x86',
    'autohotkey',
    'batchfile',
    'c9search',
    'c_cpp',
    'cirru',
    'clojure',
    'cobol',
    'coffee',
    'coldfusion',
    'csharp',
    'css',
    'curly',
    'd',
    'dart',
    'diff',
    'django',
    'dockerfile',
    'dot',
    'eiffel',
    'ejs',
    'elixir',
    'elm',
    'erlang',
    'forth',
    'ftl',
    'gcode',
    'gherkin',
    'gitignore',
    'glsl',
    'gobstones',
    'golang',
    'groovy',
    'haml',
    'handlebars',
    'haskell',
    'haxe',
    'html',
    'html_elixir',
    'html_ruby',
    'ini',
    'io',
    'jack',
    'jade',
    'java',
    'javascript',
    'json',
    'jsoniq',
    'jsp',
    'jsx',
    'julia',
    'latex',
    'lean',
    'less',
    'liquid',
    'lisp',
    'livescript',
    'logiql',
    'lsl',
    'lua',
    'luapage',
    'lucene',
    'makefile',
    'markdown',
    'mask',
    'matlab',
    'maze',
    'mel',
    'mips_assembler',
    'mushcode',
    'mysql',
    'nix',
    'nsis',
    'objectivec',
    'ocaml',
    'pascal',
    'perl',
    'pgsql',
    'php',
    'plain_text',
    'powershell',
    'praat',
    'prolog',
    'protobuf',
    'python',
    'r',
    'razor',
    'rdoc',
    'rhtml',
    'rst',
    'ruby',
    'rust',
    'sass',
    'scad',
    'scala',
    'scheme',
    'scss',
    'sh',
    'sjs',
    'smarty',
    'snippets',
    'soy_template',
    'space',
    'sql',
    'sqlserver',
    'stylus',
    'svg',
    'swift',
    'swig',
    'tcl',
    'tex',
    'text',
    'textile',
    'toml',
    'twig',
    'typescript',
    'vala',
    'vbscript',
    'velocity',
    'verilog',
    'vhdl',
    'wollok',
    'xml',
    'xquery',
    'yaml'
  ];

  /**
   * 缓存名称
   */
  const STORE_FILENAME = 'extensions';

  protected function _initialize () {
    $cache = $this->cache(static::STORE_FILENAME);
    if ($cache && count($cache)) {
      $this->defaultExtensions = $cache;
    }
  }

  /**
   * [getAvailiableTextModes 获取可用集合]
   * @return [type] [description]
   */
  public function getAvailiableTextModes() {
    return $this->availiableTextModes;
  }

  /**
   * [getDefaultExtensions 获取默认后缀]
   * @return [type] [description]
   */
  public function getDefaultExtensions() {
      return $this->defaultExtensions;
  }

  /**
   * [validateExtension 检测后缀是否合理]
   * @param  [type] $extension [description]
   * @return [type]            [description]
   */
  public function validateExtension($extension) {
      return preg_match('#^[a-z0-9\_]+$#i', $extension);
  }

  /**
   * [validTextMode 检测模式是否合理]
   * @param  [type] $mode [description]
   * @return [type]       [description]
   */
  public function validTextMode($mode)
  {
      return in_array($mode, $this->availiableTextModes);
  }

  /**
   * [FileExtTextModeForm 保存用户配置]
   */
  public function FileExtTextModeForm() {
    $extensions = input('extension/a');
    $textMode = input('textMode/a');
    $this->_condition(count($extensions) !== count($textMode), 'incorrect data send');
    $combine = [];
    foreach ($extensions as $key => $ext) {
      $ext = trim($ext);
      if ($ext) {
        $combine[$ext] = $textMode[$key];
      }
    }
    $this->cache(static::STORE_FILENAME, $combine);
    $this->success(['extensions' => $combine]);
  }

  /**
   * [processForms 表单事件处理]
   * @return [type] [description]
   */
  public function processForms() {
    $action = input('action');
    $this->_condition(!$action, 'incorrect data send');
    if ($action === 'FileExtTextModeForm') {
      return $this->FileExtTextModeForm();
    } else {
      return $this->GetFileExtTextModes();
    }
  }

  /**
   * [GetFileExtTextModes 获取文件类型]
   */
  public function GetFileExtTextModes()
  {
      $ext = $this->getDefaultExtensions();
      //the availiable extensions, which aren't removed
      $availEx = array();
      foreach ($ext as $ex => $mode) {
        if (in_array($mode, $this->availiableTextModes)) {
            $availEx[$ex] = $mode;
        }
      }
      return ['status' => 'success', 'extensions' => $availEx, 'textModes' => $this->availiableTextModes];
  }
  
  /**
   * [getTextModeSelect 渲染选择框]
   * @param  [type] $extension [description]
   * @return [type]            [description]
   */
  public function getTextModeSelect($extension)
  {
      $extension = trim(strtolower($extension));
      $options = array_map(function ($mode) use ($extension) {
        $selected = $mode === $extension ? 'selected="selected"' : '';
        return "<option {$selected}>{$mode}</option>";
      }, $this->getAvailiableTextModes());
      return '<select>' . implode('', $options) . '</select>';
  }
}
