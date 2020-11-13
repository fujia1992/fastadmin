<?php

namespace addons\ykquest\model;

use think\Model;
use traits\model\SoftDelete;

class Problem extends Model {

    use SoftDelete;

    // 表名
    protected $name = 'ykquest_problem';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    public function opetionlist() {
        return $this->belongsTo('Toption', 'id', 'problem_id', [], 'LEFT')->setEagerlyType(0);
    }

}
