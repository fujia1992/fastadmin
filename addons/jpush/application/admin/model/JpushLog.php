<?php

namespace app\admin\model;

use think\Model;

class JpushLog extends Model
{

    // 表名
    protected $name = 'jpush_log';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    protected $dateFormat = 'Y-m-d H:i:s';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
}