<?php

namespace addons\kaoshi\model\examination;

use think\Model;

class KaoshiUserExams extends Model
{

    // 表名
    protected $name = 'kaoshi_user_exams';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'starttime';
    protected $updateTime = 'lasttime';

}
