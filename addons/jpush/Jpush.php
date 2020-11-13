<?php

namespace addons\jpush;

use think\Addons;
use app\common\library\Menu;

/**
 * 插件
 */
class Jpush extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name' => 'jpush',
                'title' => '极光管理',
                'icon' => 'fa fa-list',
                'sublist' => [
                    [
                        'name' => 'jpush/push',
                        'title' => '推送管理',
                        'icon' => 'fa fa-circle-o',
                        'sublist' => [
                            ['name' => 'jpush/push/notification', 'title' => '发送自定义消息', 'ismenu' => 1],
                            ['name' => 'jpush/schedules', 'title' => '定时任务', 'sublist' => [
                                ['name' => 'jpush/schedules/index', 'title' => '查看'],
                                ['name' => 'jpush/schedules/del', 'title' => '删除']
                            ]]
                        ]
                    ],
                    [
                        'name' => 'jpush/report',
                        'title' => '统计管理',
                        'icon' => 'fa fa-circle-o',
                        'sublist' => [
                            ['name' => 'jpush/report/received', 'title' => '送达统计', 'ismenu' => 1],
                            ['name' => 'jpush/report/messages', 'title' => '送达状态查询', 'ismenu' => 1],
                        ]
                    ],
                    [
                        'name' => 'jpush/devices',
                        'title' => '设备管理',
                        'icon' => 'fa fa-circle-o',
                        'sublist' => [
                            ['name' => 'jpush/devices/get_all_attr', 'title' => '获取设备属性', 'ismenu' => 1],
                            ['name' => 'jpush/devices/set_all_attr', 'title' => '设置设备属性', 'ismenu' => 1],
                            ['name' => 'jpush/devices/get_rid_by_alias', 'title' => '获取别名对应设备', 'ismenu' => 1],
                            ['name' => 'jpush/devices/del_alias', 'title' => '删除别名', 'ismenu' => 1],
                            ['name' => 'jpush/devices/get_tags', 'title' => '获取所有标签', 'ismenu' => 1],
                            //['name' => 'jpush/devices/update_by_tag', 'title' => '用标签添加删除设备', 'ismenu' => 1],
                            //['name' => 'jpush/devices/del_tag', 'title' => '删除标签', 'ismenu' => 1],
                        ]
                    ]
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
        Menu::delete('jpush');
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable('jpush');
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable('jpush');
        return true;
    }
}