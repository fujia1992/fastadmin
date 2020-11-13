<?php

namespace addons\webide;

use app\common\library\Menu;
use app\admin\model\AuthRule;
use think\Addons;

/**
 * 插件webide
 */
class Webide extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        // first delete old menu list
        Menu::delete('webide');
        $menu = [
            [
                'name'    => 'webide',
                'title'   => '代码编辑器',
                'icon'    => 'fa fa-code',
                'remark'  => '可以在线编辑项目代码，并执行命令',
                'sublist' => [
                    ['name' => 'webide/index', 'title' => '编辑器主页'],
                    ['name' => 'webide/template', 'title' => '编辑器模板片段'],
                    ['name' => 'webide/file', 'title' => '文件管理'],
                    ['name' => 'webide/active', 'title' => '文件操作记录'],
                    ['name' => 'webide/ext', 'title' => '扩展名管理'],
                    ['name' => 'webide/setting', 'title' => '设置管理']
                ]
            ]
        ];
        Menu::create($menu);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete('webide');   
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable('webide');
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable('webide');
        return true;
    }
}
