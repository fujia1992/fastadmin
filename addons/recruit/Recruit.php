<?php

namespace addons\recruit;

use app\common\library\Menu;
use think\Addons;

/**
 * 插件
 */
class Recruit extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'recruit',
                'title'   => '招聘管理',
                'icon'    => 'fa fa-magic',
                'sublist' => [
                    [
                        'name'    => 'recruit/opencity',
                        'title'   => '开放城市',
                        'icon'    => 'fa fa-list',
                        'sublist' => [
                            ['name' => 'recruit/opencity/index', 'title' => '查看'],
                            ['name' => 'recruit/opencity/add', 'title' => '添加'],
                            ['name' => 'recruit/opencity/edit', 'title' => '修改'],
                            ['name' => 'recruit/opencity/del', 'title' => '删除'],
                            ['name' => 'recruit/opencity/multi', 'title' => '批量更新'],
                        ]
                    ],
                    [
                        'name'    => 'recruit/news',
                        'title'   => '新闻管理',
                        'icon'    => 'fa fa-list',
                        'sublist' => [
                            ['name' => 'recruit/news/index', 'title' => '查看'],
                            ['name' => 'recruit/news/add', 'title' => '添加'],
                            ['name' => 'recruit/news/edit', 'title' => '修改'],
                            ['name' => 'recruit/news/del', 'title' => '删除'],
                            ['name' => 'recruit/news/multi', 'title' => '批量更新'],
                        ]
                    ],
                    [
                        'name'    => 'recruit/jobfair',
                        'title'   => '报名列表',
                        'icon'    => 'fa fa-list',
                        'sublist' => [
                            ['name' => 'recruit/jobfair/index', 'title' => '查看'],
                            ['name' => 'recruit/jobfair/add', 'title' => '添加'],
                            ['name' => 'recruit/jobfair/edit', 'title' => '修改'],
                            ['name' => 'recruit/jobfair/del', 'title' => '删除'],
                            ['name' => 'recruit/jobfair/multi', 'title' => '批量更新'],
                        ]
                    ],
                    [
                        'name'    => 'recruit/company',
                        'title'   => '公司管理',
                        'icon'    => 'fa fa-list',
                        'sublist' => [
                            ['name' => 'recruit/company/index', 'title' => '查看'],
                            ['name' => 'recruit/company/add', 'title' => '添加'],
                            ['name' => 'recruit/company/edit', 'title' => '修改'],
                            ['name' => 'recruit/company/del', 'title' => '删除'],
                            ['name' => 'recruit/company/multi', 'title' => '批量更新'],
                        ]
                    ],
                    [
                        'name'    => 'recruit/job',
                        'title'   => '职位管理',
                        'icon'    => 'fa fa-list',
                        'sublist' => [
                            ['name' => 'recruit/job/index', 'title' => '查看'],
                            ['name' => 'recruit/job/add', 'title' => '添加'],
                            ['name' => 'recruit/job/edit', 'title' => '修改'],
                            ['name' => 'recruit/job/del', 'title' => '删除'],
                            ['name' => 'recruit/job/multi', 'title' => '批量更新'],
                        ]
                    ],
                    [
                        'name'    => 'recruit/resume',
                        'title'   => '简历管理',
                        'icon'    => 'fa fa-list',
                        'sublist' => [
                            ['name' => 'recruit/resume/index', 'title' => '查看'],
                            ['name' => 'recruit/resume/add', 'title' => '添加'],
                            ['name' => 'recruit/resume/edit', 'title' => '修改'],
                            ['name' => 'recruit/resume/del', 'title' => '删除'],
                            ['name' => 'recruit/resume/multi', 'title' => '批量更新'],
                        ]
                    ],
                    [
                        'name'    => 'recruit/resumedelivery',
                        'title'   => '简历投递历史',
                        'icon'    => 'fa fa-list',
                        'sublist' => [
                            ['name' => 'recruit/resumedelivery/index', 'title' => '查看'],
                            ['name' => 'recruit/resumedelivery/add', 'title' => '添加'],
                            ['name' => 'recruit/resumedelivery/edit', 'title' => '修改'],
                            ['name' => 'recruit/resumedelivery/del', 'title' => '删除'],
                            ['name' => 'recruit/resumedelivery/multi', 'title' => '批量更新'],
                            ['name' => 'recruit/resumedelivery/detail', 'title' => '详情'],
                        ]
                    ],
                    [
                        'name'    => 'recruit/workforce',
                        'title'   => '劳动力信息库',
                        'icon'    => 'fa fa-list',
                        'sublist' => [
                            ['name' => 'recruit/workforce/index', 'title' => '查看'],
                            ['name' => 'recruit/workforce/add', 'title' => '添加'],
                            ['name' => 'recruit/workforce/edit', 'title' => '修改'],
                            ['name' => 'recruit/workforce/del', 'title' => '删除'],
                            ['name' => 'recruit/workforce/multi', 'title' => '批量更新'],
                        ]
                    ],
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
        Menu::delete('recruit');
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable('recruit');
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable('recruit');
        return true;
    }

    /**
     * 实现钩子方法
     * @return mixed
     */
    public function testhook($param)
    {
        // 调用钩子时候的参数信息
        print_r($param);
        // 当前插件的配置信息，配置信息存在当前目录的config.php文件中，见下方
        print_r($this->getConfig());
        // 可以返回模板，模板文件默认读取的为插件目录中的文件。模板名不能为空！
        //return $this->fetch('view/info');
    }

}
