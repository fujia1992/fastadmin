<?php
namespace addons\webide\service;

/**
 * 全局设置
 * @author: vace(ocdo@qq.com)
 * @description: 编辑器设置以及用户配置管理
 */
class CSetting extends CBase{

  /**
   * [$projectIgnorePath 忽略目录]
   * @var array
   */
  public static $projectIgnorePath = [];

  /**
   * [$projectIgnorePath 忽略文件后缀]
   * @var array
   */
  public static $projectIgnoreFileExt = [];

  /**
   * [$projectRootName 项目名称]
   * @var string
   */
  public static $projectRootName = 'Root';


  /**
   * [$isIsolationSetting 配置是否隔离]
   * @var boolean
   */
  public static $isIsolationSetting = true;

  /**
   * [$isDebugMode 是否为debug模式]
   * @var boolean
   */
  public static $isDebugMode = false;

  protected $setting;

  protected function _initialize () {
    $setting = $this->cache('setting');
    if (!$setting) {
      $setting = ['codiad.username' => $this->auth->nickname];
      $this->cache('setting', $setting);
    }
    $this->setting = $setting;
  }

  /**
   * [getSetting 获取前端webide配置]
   * @return [type] [description]
   */
  public function getSetting () {
    return $this->setting;
  }

  /**
   * [load 加载配置]
   * @return [type] [description]
   */
  public function load () {
    return $this->success($this->setting);
  }

  /**
   * [save 保存配置]
   * @return [type] [description]
   */
  public function save () {
    $setting = json_decode(input('settings'), true);
    $setting['codiad.username'] = $this->auth->nickname;
    $this->cache('setting', $setting);
    return $this->success(['setting' => $setting]);
  }

  public static function initUserSetting ($setting) {
    if (isset($setting['isIsolationSetting'])) {
      CSetting::setIsolationSetting($setting['isIsolationSetting']);
    }
    if (isset($setting['projectRootName'])) {
      CSetting::setProjectRootName($setting['projectRootName']);
    }
    if (isset($setting['fileIgnoreList'])) {
      CSetting::setProjectIgnorePath($setting['fileIgnoreList']);
    }
    if (isset($setting['fileIgnoreFileExt'])) {
      CSetting::setProjectIgnoreFileExt($setting['fileIgnoreFileExt']);
    }
  }

  /**
   * [setProjectIgnorePath 设置忽略目录]
   * @param [type] $path [description]
   */
  public static function setProjectIgnorePath ($path) {
    self::$projectIgnorePath = array_filter(array_map('trim', explode('|', $path)));
  }

  /**
   * [setProjectIgnorePath 设置忽略目录]
   * @param [type] $path [description]
   */
  public static function setProjectIgnoreFileExt ($path) {
    self::$projectIgnoreFileExt = array_filter(array_map('trim', explode('|', $path)));
  }

  /**
   * [setProjectRootName 设置项目名称]
   * @param [type] $name [description]
   */
  public static function setProjectRootName ($name) {
    self::$projectRootName = $name;
  }


  public static function setIsolationSetting ($value) {
    self::$isIsolationSetting = !!$value;
  }

  /**
   * [getSlideMenu 获取侧边栏二级菜单]
   * @return [type] [description]
   */
  public static function getSlideMenu () {
    return json_decode(self::$configSlideMenu, true);
  }

  /**
   * [getSlideBar 获取侧边栏菜单]
   * @return [type] [description]
   */
  public static function getSlideBar () {
    return json_decode(self::$configSlideBar, true);
  }

  /**
   * [getScriptLoadList 获取预加载js]
   * @return [type] [description]
   */
  public static function getScriptLoadList () {
    if (self::$isDebugMode) {
      return self::$debugLoadJs;      
    }
    return [
      'scripts.js'
    ];
  }

  /**
   * [$configSlideMenu 侧边栏菜单]
   * @var [type]
   */
  protected static $configSlideMenu = <<<EOF
[{
  "title": "New File",
  "icon": "icon-doc-text",
  "applies-to": "directory-only",
  "onclick": "codiad.filemanager.createNode($('#context-menu').attr('data-path'),'file');"
}, {
  "title": "New Folder",
  "icon": "icon-folder",
  "applies-to": "directory-only",
  "onclick": "codiad.filemanager.createNode($('#context-menu').attr('data-path'),'directory');"
}, {
  "title": "Break",
  "icon": null,
  "applies-to": "directory-only",
  "onclick": null
}, {
  "title": "Search",
  "icon": "icon-target",
  "applies-to": "directory-only",
  "onclick": "codiad.filemanager.search($('#context-menu').attr('data-path'));"
}, {
  "title": "Break",
  "icon": null,
  "applies-to": "directory-only",
  "onclick": null
}, {
  "title": "Upload Files",
  "icon": "icon-upload",
  "applies-to": "directory-only",
  "onclick": "codiad.filemanager.uploadToNode($('#context-menu').attr('data-path'));"
}, {
  "title": "Preview",
  "icon": "icon-eye",
  "applies-to": "both no-external",
  "onclick": "codiad.filemanager.openInBrowser($('#context-menu').attr('data-path'));"
}, {
  "title": "Break",
  "icon": null,
  "applies-to": "file-only no-external",
  "onclick": null
}, {
  "title": "Break",
  "icon": null,
  "applies-to": "directory-only",
  "onclick": null
}, {
  "title": "Copy",
  "icon": "icon-doc",
  "applies-to": "both",
  "onclick": "codiad.filemanager.copyNode($('#context-menu').attr('data-path'));"
}, {
  "title": "Paste",
  "icon": "icon-docs",
  "applies-to": "directory-only",
  "onclick": "codiad.filemanager.pasteNode($('#context-menu').attr('data-path'));"
}, {
  "title": "Break",
  "icon": null,
  "applies-to": "non-root",
  "onclick": null
}, {
  "title": "Rename",
  "icon": "icon-pencil",
  "applies-to": "non-root",
  "onclick": "codiad.filemanager.renameNode($('#context-menu').attr('data-path'));"
}, {
  "title": "Break",
  "icon": null,
  "applies-to": "non-root",
  "onclick": null
}, {
  "title": "Delete",
  "icon": "icon-cancel-circled",
  "applies-to": "non-root",
  "onclick": "codiad.filemanager.deleteNode($('#context-menu').attr('data-path'));"
}, {
  "title": "Break",
  "icon": null,
  "applies-to": "both no-external",
  "onclick": null
}, {
  "title": "Download",
  "icon": "icon-download",
  "applies-to": "both no-external",
  "onclick": "codiad.filemanager.download($('#context-menu').attr('data-path'));"
}, {
  "title": "Break",
  "icon": null,
  "applies-to": "directory-only",
  "onclick": null
}, {
  "title": "Rescan",
  "icon": "icon-arrows-ccw",
  "applies-to": "directory-only",
  "onclick": "codiad.filemanager.rescan($('#context-menu').attr('data-path'));"
}]
EOF;
  
  /**
   * [$configSlideBar 侧边栏功能]
   * @var [type]
   */
  protected static $configSlideBar = <<<EOF
[{
  "title": "Save",
  "admin": false,
  "icon": "icon-install",
  "onclick": "codiad.active.save();"
}, {
  "title": "Save All",
  "admin": false,
  "icon": "icon-install",
  "onclick": "codiad.active.saveAll();"
}, {
  "title": "Account",
  "admin": false,
  "icon": null,
  "onclick": null
}, {
  "title": "Settings",
  "admin": false,
  "icon": "icon-doc-text",
  "onclick": "codiad.settings.show();"
}, {
  "title": "Help",
  "admin": false,
  "icon": "icon-help",
  "onclick": "window.open('https://www.fastadmin.net/store/webide.html');"
}]
EOF;

  protected static $debugLoadJs = [
    "js/js_jquery-1.7.2.min.js",
    "js/js_jquery-ui-1.8.23.custom.min.js",
    "js/js_jquery.css3.min.js",
    "js/js_jquery.easing.js",
    "js/js_jquery.toastmessage.js",
    "js/js_amplify.min.js",
    "js/js_localstorage.js",
    "js/js_jquery.hoverIntent.min.js",
    "js/js_system.js",
    "js/js_sidebars.js",
    "js/js_modal.js",
    "js/js_message.js",
    "js/js_jsend.js",
    "js/components_active_init.js",
    "js/components_autocomplete_init.js",
    "js/components_editor_init.js",
    "js/components_fileext_textmode_init.js",
    "js/components_filemanager_init.js",
    "js/components_finder_init.js",
    "js/components_keybindings_init.js",
    "js/components_poller_init.js",
    "js/components_project_init.js",
    "js/components_settings_init.js",
    "js/components_worker_manager_init.js",
    'js/jquery.fileupload.js',
    'js/jquery.iframe-transport.js',
    'js/jquery.ui.widget.js'
  ];
}
