<?php

namespace app\admin\controller;

use addons\webide\service\CUser;
use addons\webide\service\CActive;
use addons\webide\service\CSetting;

use app\common\controller\Backend;
use app\admin\model\AdminLog;
use think\addons\Controller;
use think\Config;
use think\Lang;
use think\Response;

/**
 * 后台页面渲染
 * @author: vace(ocdo@qq.com)
 * @description: 代码编辑器页面渲染、以及API
 */
class Webide extends Backend
{

    protected $addonSetting;

    public function _initialize()
    {
        AdminLog::setTitle('Webide-代码编辑器');
        Lang::load(ADDON_PATH . 'webide' . DS . 'lang' . DS . 'zh-cn.php');
        // 加载配置文件
        CSetting::initUserSetting(($this->addonSetting = get_addon_config('webide')));
        parent::_initialize();
    }

    /**
     * 布局模板
     * @var string
     */

    public function index()
    {
        $isSuperAdmin = true;
        $globalSearchEnable = false; // 是否开启全局搜索，实测，很慢，不建议开启

        $contextMenu = CSetting::getSlideMenu();
        $rightBar = CSetting::getSlideBar();
        $scriptLoadList = CSetting::getScriptLoadList();

        $this->view->assign('lang', Lang::get(null, []));
        $this->view->assign('webide', [
            'baseAcePath' => $this->addonSetting['baseAcePath'] ?: '//cdnjs.cloudflare.com/ajax/libs/ace/1.3.3/'
        ]);
        $this->view->assign('editor', compact(
            'globalSearchEnable',
            'contextMenu',
            'rightBar',
            'isSuperAdmin',
            'scriptLoadList'
        ));
        $this->view->engine->layout(false);
        $this->view->engine->layout('webide/layout');
        return $this->view->fetch();
    }

    public function template()
    {
        $this->view->engine->layout(false);
        $template = $this->view->fetch();
        return die($template);
    }

    public function _empty($name)
    {
        $service = '\\addons\\webide\\service\\C' . ucfirst($name) . '::execute';
        return call_user_func($service, []);
    }

}
