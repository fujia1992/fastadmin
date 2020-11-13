<?php

namespace addons\ykquest;

use app\common\library\Menu;
use think\Addons;

/**
 * 插件
 */
class Ykquest extends Addons {

    /**
     * 插件安装方法
     * @return bool
     */
    public function install() {
        $menu = [
                [
                'name' => 'ykquest',
                'title' => '问卷调查',
                'icon' => 'fa fa-file-text-o',
                'remark' => '',
                'sublist' => [
                        [
                        "name" => "ykquest/survey_type",
                        "title" => "问卷类型管理",
                        "ismenu" => 1,
                        "sublist" => [
                                [
                                "name" => "ykquest/survey_type/add",
                                "title" => "添加"
                            ],
                                [
                                "name" => "ykquest/survey_type/del",
                                "title" => "删除"
                            ],
                                [
                                "name" => "ykquest/survey_type/index",
                                "title" => "查看"
                            ],
                                [
                                "name" => "ykquest/survey_type/recyclebin",
                                "title" => "回收站"
                            ],
                                [
                                "name" => "ykquest/survey_type/edit",
                                "title" => "编辑"
                            ],
                                [
                                "name" => "ykquest/survey_type/destroy",
                                "title" => "真实删除"
                            ],
                                [
                                "name" => "ykquest/survey_type/restore",
                                "title" => "还原"
                            ],
                                [
                                "name" => "ykquest/survey_type/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                        [
                        "name" => "ykquest/survey",
                        "title" => "问卷管理",
                        "ismenu" => 1,
                        "sublist" => [
                                [
                                "name" => "ykquest/survey/index",
                                "title" => "查看"
                            ],
                                [
                                "name" => "ykquest/survey/del",
                                "title" => "删除"
                            ],
                                [
                                "name" => "ykquest/survey/add",
                                "title" => "添加"
                            ],
                                [
                                "name" => "ykquest/survey/multi",
                                "title" => "批量更新"
                            ],
                                [
                                "name" => "ykquest/survey/detail",
                                "title" => "预览"
                            ],
                                [
                                "name" => "ykquest/survey/recyclebin",
                                "title" => "回收站"
                            ],
                                [
                                "name" => "ykquest/survey/edit",
                                "title" => "编辑"
                            ],
                                [
                                "name" => "ykquest/survey/destroy",
                                "title" => "真实删除"
                            ],
                                [
                                "name" => "ykquest/survey/restore",
                                "title" => "还原"
                            ]
                        ]
                    ],
                        [
                        "name" => "ykquest/problem",
                        "title" => "问题管理",
                        "ismenu" => 1,
                        "sublist" => [
                                [
                                "name" => "ykquest/problem/index",
                                "title" => "查看"
                            ],
                                [
                                "name" => "ykquest/problem/add",
                                "title" => "添加"
                            ],
                                [
                                "name" => "ykquest/problem/edit",
                                "title" => "编辑"
                            ],
                                [
                                "name" => "ykquest/problem/del",
                                "title" => "删除"
                            ],
                                [
                                "name" => "ykquest/problem/recyclebin",
                                "title" => "回收站"
                            ],
                                [
                                "name" => "ykquest/problem/destroy",
                                "title" => "真实删除"
                            ],
                                [
                                "name" => "ykquest/problem/restore",
                                "title" => "还原"
                            ],
                                [
                                "name" => "ykquest/problem/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                        [
                        "name" => "ykquest/reply",
                        "title" => "答卷管理",
                        "ismenu" => 1,
                        "sublist" => [
                                [
                                "name" => "ykquest/reply/index",
                                "title" => "查看"
                            ],
                                [
                                "name" => "ykquest/reply/detail",
                                "title" => "答题详情"
                            ],
                                [
                                "name" => "ykquest/reply/recyclebin",
                                "title" => "回收站"
                            ],
                                [
                                "name" => "ykquest/reply/add",
                                "title" => "添加"
                            ],
                                [
                                "name" => "ykquest/reply/edit",
                                "title" => "编辑"
                            ],
                                [
                                "name" => "ykquest/reply/del",
                                "title" => "删除"
                            ],
                                [
                                "name" => "ykquest/reply/destroy",
                                "title" => "真实删除"
                            ],
                                [
                                "name" => "ykquest/reply/restore",
                                "title" => "还原"
                            ],
                                [
                                "name" => "ykquest/reply/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                        [
                        "name" => "ykquest/answerer",
                        "title" => "答卷者",
                        "ismenu" => 1,
                        "sublist" => [
                                [
                                "name" => "ykquest/answerer/index",
                                "title" => "查看"
                            ],
                                [
                                "name" => "ykquest/answerer/recyclebin",
                                "title" => "回收站"
                            ],
                                [
                                "name" => "ykquest/answerer/add",
                                "title" => "添加"
                            ],
                                [
                                "name" => "ykquest/answerer/edit",
                                "title" => "编辑"
                            ],
                                [
                                "name" => "ykquest/answerer/del",
                                "title" => "删除"
                            ],
                                [
                                "name" => "ykquest/answerer/destroy",
                                "title" => "真实删除"
                            ],
                                [
                                "name" => "ykquest/answerer/restore",
                                "title" => "还原"
                            ],
                                [
                                "name" => "ykquest/answerer/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                        [
                        "name" => "ykquest/myanswer",
                        "title" => "问卷总统计",
                        "ismenu" => 1,
                        "sublist" => [
                                [
                                "name" => "ykquest/myanswer/index",
                                "title" => "查看"
                            ],
                                [
                                "name" => "ykquest/myanswer/recyclebin",
                                "title" => "回收站"
                            ],
                                [
                                "name" => "ykquest/myanswer/add",
                                "title" => "添加"
                            ],
                                [
                                "name" => "ykquest/myanswer/edit",
                                "title" => "编辑"
                            ],
                                [
                                "name" => "ykquest/myanswer/del",
                                "title" => "删除"
                            ],
                                [
                                "name" => "ykquest/myanswer/destroy",
                                "title" => "真实删除"
                            ],
                                [
                                "name" => "ykquest/myanswer/restore",
                                "title" => "还原"
                            ],
                                [
                                "name" => "ykquest/myanswer/multi",
                                "title" => "批量更新"
                            ]
                        ]
                    ],
                        [
                        "name" => "ykquest/detail",
                        "title" => "详细统计",
                        "ismenu" => 1,
                        "sublist" => [
                                [
                                "name" => "ykquest/detail/index",
                                "title" => "查看"
                            ],
                                [
                                "name" => "ykquest/detail/detail2",
                                "title" => "文本题展示"
                            ],
                                [
                                "name" => "ykquest/detail/detail1",
                                "title" => "单选或者多选题统计"
                            ],
                                [
                                "name" => "ykquest/detail/recyclebin",
                                "title" => "回收站"
                            ],
                                [
                                "name" => "ykquest/detail/add",
                                "title" => "添加"
                            ],
                                [
                                "name" => "ykquest/detail/edit",
                                "title" => "编辑"
                            ],
                                [
                                "name" => "ykquest/detail/del",
                                "title" => "删除"
                            ],
                                [
                                "name" => "ykquest/detail/destroy",
                                "title" => "真实删除"
                            ],
                                [
                                "name" => "ykquest/detail/restore",
                                "title" => "还原"
                            ],
                                [
                                "name" => "ykquest/detail/multi",
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
    public function uninstall() {
        Menu::delete("ykquest");
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable() {
        Menu::enable("ykquest");
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable() {
        Menu::disable("ykquest");
        return true;
    }

    /**
     * 实现钩子方法
     * @return mixed
     */
    public function testhook($param) {
        // 调用钩子时候的参数信息
        print_r($param);
        // 当前插件的配置信息，配置信息存在当前目录的config.php文件中，见下方
        print_r($this->getConfig());
        // 可以返回模板，模板文件默认读取的为插件目录中的文件。模板名不能为空！
        //return $this->fetch('view/info');
    }

}
