<?php

namespace app\admin\model\ykquest;

use think\Model;
use traits\model\SoftDelete;

class Reply extends Model {

    use SoftDelete;

    // 表名
    protected $name = 'ykquest_reply';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';
    // 追加属性
    protected $append = [
        'option_type_text'
    ];

    public function getOptionTypeList() {
        return ['0' => __('Option_type 0'), '1' => __('Option_type 1'), '2' => __('Option_type 2'), '3' => __('Option_type 3'), '4' => __('Option_type 4'), '5' => __('Option_type 5')];
    }

    public function getOptionTypeTextAttr($value, $data) {
        $value = $value ? $value : (isset($data['option_type']) ? $data['option_type'] : '');
        $list = $this->getOptionTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function problem() {
        return $this->belongsTo('Problem', 'problem_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
