<?php

namespace addons\kaoshi;

use app\common\library\Menu;
use think\Addons;

/**
 * 插件
 */
class Kaoshi extends Addons {

	/**
	 * 插件安装方法
	 * @return bool
	 */
	public function install() {
		$menu =  [
        [
            'name' => 'kaoshi',
            'title' => '考试系统',
            'icon' => 'fa fa-file-text-o',
            'remark' => '',
            'sublist' =>[
			[
				"name" => "kaoshi/subject",
				"title" => "科目管理",
				"icon" => "fa fa-th-large",
				"ismenu" => 1,
				"sublist" => [
					[
						"name" => "kaoshi/subject/index",
						"title" => "查看",
					],
					[
						"name" => "kaoshi/subject/recyclebin",
						"title" => "回收站",
					],
					[
						"name" => "kaoshi/subject/add",
						"title" => "添加",
					],
					[
						"name" => "kaoshi/subject/edit",
						"title" => "编辑",
					],
					[
						"name" => "kaoshi/subject/del",
						"title" => "删除",
					],
					[
						"name" => "kaoshi/subject/destroy",
						"title" => "真实删除",
					],
					[
						"name" => "kaoshi/subject/restore",
						"title" => "还原",
					],
					[
						"name" => "kaoshi/subject/multi",
						"title" => "批量更新",
					],
				],
			],
            [
                "name" => "kaoshi/student",
                "title" => "学生管理",
                "icon" => "fa fa-mortar-board",
                "ismenu" => 1,
                "sublist" => [
                    [
                        "name" => "kaoshi/student/index",
                        "title" => "查看",
                    ],
                    [
                        "name" => "kaoshi/student/import",
                        "title" => "导入",
                    ],
                    [
                        "name" => "kaoshi/student/add",
                        "title" => "添加",
                    ],
                    [
                        "name" => "kaoshi/student/edit",
                        "title" => "编辑",
                    ],
                    [
                        "name" => "kaoshi/student/del",
                        "title" => "删除",
                    ],
                    [
                        "name" => "kaoshi/student/multi",
                        "title" => "批量更新",
                    ],
                ],
            ],
			[
				"name" => "kaoshi/examination",
				"title" => "考试管理",
				"icon" => "fa fa-list",
				"ismenu" => 1,
				"sublist" => [
					[
						"name" => "kaoshi/examination/questions",
						"title" => "试题管理",
						"icon" => "fa fa-file",
						"ismenu" => 1,
						"sublist" => [
							[
								"name" => "kaoshi/examination/questions/index",
								"title" => "查看",
							],
							[
								"name" => "kaoshi/examination/questions/recyclebin",
								"title" => "回收站",
							],
							[
								"name" => "kaoshi/examination/questions/add",
								"title" => "添加",
							],
							[
								"name" => "kaoshi/examination/questions/edit",
								"title" => "编辑",
							],
							[
								"name" => "kaoshi/examination/questions/del",
								"title" => "删除",
							],
							[
								"name" => "kaoshi/examination/questions/destroy",
								"title" => "真实删除",
							],
							[
								"name" => "kaoshi/examination/questions/restore",
								"title" => "还原",
							],
							[
								"name" => "kaoshi/examination/questions/multi",
								"title" => "批量更新",
							],
							[
								"name" => "kaoshi/examination/questions/import",
								"title" => "导入",
							],
						],
					],
					[
						"name" => "kaoshi/examination/exams",
						"title" => "考卷管理",
						"icon" => "fa fa-paste",
						"ismenu" => 1,
						"sublist" => [
							[
								"name" => "kaoshi/examination/exams/index",
								"title" => "查看",
							],
							[
								"name" => "kaoshi/examination/exams/recyclebin",
								"title" => "回收站",
							],
							[
								"name" => "kaoshi/examination/exams/add",
								"title" => "添加",
							],
							[
								"name" => "kaoshi/examination/exams/edit",
								"title" => "编辑",
							],
							[
								"name" => "kaoshi/examination/exams/del",
								"title" => "删除",
							],
							[
								"name" => "kaoshi/examination/exams/destroy",
								"title" => "真实删除",
							],
							[
								"name" => "kaoshi/examination/exams/restore",
								"title" => "还原",
							],
							[
								"name" => "kaoshi/examination/exams/multi",
								"title" => "批量更新",
							],
						],
					],
					[
						"name" => "kaoshi/examination/plan",
						"title" => "计划安排",
						"icon" => "fa fa-hourglass-start",
						"ismenu" => 1,
						"sublist" => [
							[
								"name" => "kaoshi/examination/plan/index",
								"title" => "查看",
							],
							[
								"name" => "kaoshi/examination/plan/recyclebin",
								"title" => "回收站",
							],
							[
								"name" => "kaoshi/examination/plan/add",
								"title" => "添加",
							],
							[
								"name" => "kaoshi/examination/plan/edit",
								"title" => "编辑",
							],
							[
								"name" => "kaoshi/examination/plan/del",
								"title" => "删除",
							],
							[
								"name" => "kaoshi/examination/plan/destroy",
								"title" => "真实删除",
							],
							[
								"name" => "kaoshi/examination/plan/restore",
								"title" => "还原",
							],
							[
								"name" => "kaoshi/examination/plan/multi",
								"title" => "批量更新",
							],
						],
					],
					[
						"name" => "kaoshi/examination/user_plan",
						"title" => "学生安排",
						"icon" => "fa fa-group",
						"ismenu" => 0,
						"sublist" => [
							[
								"name" => "kaoshi/examination/user_plan/index",
								"title" => "查看",
							],
							[
								"name" => "kaoshi/examination/user_plan/add",
								"title" => "添加",
							],
							[
								"name" => "kaoshi/examination/user_plan/del",
								"title" => "删除",
							],
							[
								"name" => "kaoshi/examination/user_plan/multi",
								"title" => "批量更新",
							],
							[
								"name" => "kaoshi/examination/user_plan/edit",
								"title" => "修改",
							],
						],
					],
				],
			],
			[
				"name" => "kaoshi/examination/user_exams",
				"title" => "统计管理",
				"icon" => "fa fa-line-chart",
				"ismenu" => 1,
				"sublist" => [
					[
						"name" => "kaoshi/examination/plan/study",
						"title" => "学习统计",
						"icon" => "fa fa-book",
						"ismenu" => 1,
					],
					[
						"name" => "kaoshi/examination/plan/exam",
						"title" => "考试统计",
						"icon" => "fa fa-laptop",
						"ismenu" => 1,
					],
					[
						"name" => "kaoshi/examination/user_exams/studyrank",
						"title" => "学习排行榜",
						"icon" => "fa fa-line-chart",
						"ismenu" => 1,
					],
					[
						"name" => "kaoshi/examination/user_exams/examrank",
						"title" => "考试排行榜",
						"icon" => "fa fa-graduation-cap",
						"ismenu" => 1,
					],
					[
						"name" => "kaoshi/examination/user_exams/users",
						"title" => "参与学生",
						"icon" => "fa fa-users",
					],
				],
			],
]]
		];
		Menu::create($menu);
		return true;
	}

	/**
	 * 插件卸载方法
	 * @return bool
	 */
	public function uninstall() {
		Menu::delete("kaoshi");
		return true;
	}

	/**
	 * 插件启用方法
	 * @return bool
	 */
	public function enable() {
		Menu::enable("kaoshi");
		return true;
	}

	/**
	 * 插件禁用方法
	 * @return bool
	 */
	public function disable() {
		Menu::disable("kaoshi");
		return true;
	}


}
