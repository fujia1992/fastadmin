<?php

namespace app\admin\model\ykquest;

use think\Model;
use traits\model\SoftDelete;

class Survey extends Model {

    use SoftDelete;

    // 表名
    protected $name = 'ykquest_survey';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';
    // 追加属性
    protected $append = [
        'status_text',
        'starttime_text',
        'endtime_text'
    ];

    public function getStatusList() {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }

    public function getStatusTextAttr($value, $data) {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getStarttimeTextAttr($value, $data) {
        $value = $value ? $value : (isset($data['starttime']) ? $data['starttime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public function getEndtimeTextAttr($value, $data) {
        $value = $value ? $value : (isset($data['endtime']) ? $data['endtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setStarttimeAttr($value) {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setEndtimeAttr($value) {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    public function surveytype() {
        return $this->belongsTo('SurveyType', 'type_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
