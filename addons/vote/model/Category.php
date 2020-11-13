<?php

namespace addons\vote\model;

use fast\Tree;
use think\Model;


class Category extends Model
{

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'vote_category';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    protected static $config = [];

    protected static function init()
    {
        $config = get_addon_config('vote');
        self::$config = $config;
    }

    public static function getSubjectCategoryList($subject_id)
    {
        $categoryList = self::where('subject_id', $subject_id)->where('status', 'normal')->order('weigh DESC,id DESC')->select();

        return $categoryList;

        $categoryList = collection($categoryList)->toArray();
        $result = Tree::instance()->init($categoryList)->getTreeArray(0);
        return $result;
    }
}
