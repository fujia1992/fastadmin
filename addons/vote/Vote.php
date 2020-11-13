<?php

namespace addons\vote;

use app\common\library\Menu;
use think\Addons;
use think\Request;

/**
 * 投票插件
 */
class Vote extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [

                "name"    => "vote",
                "title"   => "投票管理",
                "ismenu"  => 1,
                "icon"    => "fa fa-bar-chart",
                "sublist" => [
                    [
                        "name"    => "vote/subject",
                        "title"   => "投票主题管理",
                        "ismenu"  => 1,
                        "sublist" => [
                            [
                                "name"  => "vote/subject/index",
                                "title" => "查看"
                            ],
                            [
                                "name"  => "vote/subject/add",
                                "title" => "添加"
                            ],
                            [
                                "name"  => "vote/subject/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name"  => "vote/subject/del",
                                "title" => "删除"
                            ],
                            [
                                "name"  => "vote/subject/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                    [
                        "name"    => "vote/player",
                        "title"   => "投票选手管理",
                        "ismenu"  => 1,
                        "icon"    => "fa fa-users",
                        "sublist" => [
                            [
                                "name"  => "vote/player/index",
                                "title" => "查看"
                            ],
                            [
                                "name"  => "vote/player/add",
                                "title" => "添加"
                            ],
                            [
                                "name"  => "vote/player/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name"  => "vote/player/del",
                                "title" => "删除"
                            ],
                            [
                                "name"  => "vote/player/multi",
                                "title" => "批量更新"
                            ],
                            [
                                "name"  => "vote/player/import",
                                "title" => "导入"
                            ],
                            [
                                "name"  => "vote/player/detail",
                                "title" => "详情"
                            ]
                        ]
                    ],
                    [
                        "name"    => "vote/category",
                        "title"   => "主题分类管理",
                        "ismenu"  => 0,
                        "icon"    => "fa fa-leaf",
                        "sublist" => [
                            [
                                "name"  => "vote/category/index",
                                "title" => "查看"
                            ],
                            [
                                "name"  => "vote/category/add",
                                "title" => "添加"
                            ],
                            [
                                "name"  => "vote/category/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name"  => "vote/category/del",
                                "title" => "删除"
                            ],
                            [
                                "name"  => "vote/category/multi",
                                "title" => "批量更新"
                            ],
                            [
                                "name"  => "vote/category/import",
                                "title" => "导入"
                            ]
                        ]
                    ],
                    [
                        "name"    => "vote/statistics",
                        "title"   => "主题统计报表",
                        "ismenu"  => 0,
                        "icon"    => "fa fa-bar-chart",
                        "sublist" => [
                            [
                                "name"  => "vote/statistics/index",
                                "title" => "查看"
                            ]
                        ]
                    ],
                    [
                        "name"    => "vote/record",
                        "title"   => "投票记录管理",
                        "ismenu"  => 1,
                        "icon"    => "fa fa-list",
                        "sublist" => [
                            [
                                "name"  => "vote/record/index",
                                "title" => "查看"
                            ],
                            [
                                "name"  => "vote/record/add",
                                "title" => "添加"
                            ],
                            [
                                "name"  => "vote/record/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name"  => "vote/record/del",
                                "title" => "删除"
                            ],
                            [
                                "name"  => "vote/record/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                    [
                        "name"    => "vote/comment",
                        "title"   => "投票评论管理",
                        "ismenu"  => 1,
                        "icon"    => "fa fa-comment",
                        "sublist" => [
                            [
                                "name"  => "vote/comment/index",
                                "title" => "查看"
                            ],
                            [
                                "name"  => "vote/comment/add",
                                "title" => "添加"
                            ],
                            [
                                "name"  => "vote/comment/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name"  => "vote/comment/del",
                                "title" => "删除"
                            ],
                            [
                                "name"  => "vote/comment/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                    [
                        "name"    => "vote/fields",
                        "title"   => "投票字段管理",
                        "ismenu"  => 0,
                        "icon"    => "fa fa-circle-o",
                        "sublist" => [
                            [
                                "name"  => "vote/fields/index",
                                "title" => "查看"
                            ],
                            [
                                "name"  => "vote/fields/add",
                                "title" => "添加"
                            ],
                            [
                                "name"  => "vote/fields/edit",
                                "title" => "编辑"
                            ],
                            [
                                "name"  => "vote/fields/del",
                                "title" => "删除"
                            ],
                            [
                                "name"  => "vote/fields/multi",
                                "title" => "批量更新"
                            ]
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
        Menu::delete("vote");
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable("vote");
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable("vote");
        return true;
    }

    /**
     * 脚本替换
     */
    public function viewFilter(& $content)
    {
        $request = \think\Request::instance();
        $dispatch = $request->dispatch();

        if ($request->module() || !isset($dispatch['method'][0]) || $dispatch['method'][0] != '\think\addons\Route') {
            return;
        }
        $addon = isset($dispatch['var']['addon']) ? $dispatch['var']['addon'] : $request->param('addon');
        if ($addon != 'vote') {
            return;
        }
        $style = '';
        $script = '';
        $result = preg_replace_callback("/<(script|style)\s(data\-render=\"(script|style)\")([\s\S]*?)>([\s\S]*?)<\/(script|style)>/i", function ($match) use (&$style, &$script) {
            if (isset($match[1]) && in_array($match[1], ['style', 'script'])) {
                ${$match[1]} .= str_replace($match[2], '', $match[0]);
            }
            return '';
        }, $content);
        $content = preg_replace_callback('/^\s+(\{__STYLE__\}|\{__SCRIPT__\})\s+$/m', function ($matches) use ($style, $script) {
            return $matches[1] == '{__STYLE__}' ? $style : $script;
        }, $result ? $result : $content);
    }

    /**
     * 会员中心边栏后
     * @return mixed
     * @throws \Exception
     */
    public function userSidenavAfter()
    {
        $config = get_addon_config('vote');
        if (!$config['usersidebar']) {
            return '';
        }
        $request = Request::instance();
        $controllername = strtolower($request->controller());
        $actionname = strtolower($request->action());
        $data = [
            'controllername' => $controllername,
            'actionname'     => $actionname,
            'usersidebar'    => explode(',', $config['usersidebar']),
        ];
        return $this->fetch('view/hook/user_sidenav_after', $data);
    }

}
