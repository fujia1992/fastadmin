<?php

namespace app\admin\model\ykquest;

use think\Model;
use traits\model\SoftDelete;

class Myanswer extends Model {

    use SoftDelete;

    // 表名
    protected $name = 'ykquest_myanswer';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';
    // 追加属性
    protected $append = [
    ];

    public function surveytype() {
        return $this->belongsTo('SurveyType', 'answerer_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
