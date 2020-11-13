<?php

namespace addons\vote\model;

use think\Model;


class Record extends Model
{

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'vote_record';

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

    public function player()
    {
        return $this->belongsTo('Player', 'player_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function subject()
    {
        return $this->belongsTo('Subject', 'subject_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
