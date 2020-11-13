<?php

namespace app\admin\model\kaoshi\examination;

use think\Model;
use traits\model\SoftDelete;

class KaoshiPlan extends Model
{

    use SoftDelete;


    // 表名
    protected $name = 'kaoshi_plan';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'type_text',
        'starttime_text',
        'endtime_text'
    ];


    public function getTypeList()
    {
        return ['0' => __('Type 0'), '1' => __('Type 1')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStarttimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['starttime']) ? $data['starttime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getEndtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['endtime']) ? $data['endtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setStarttimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setEndtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    public function subject()
    {
        return $this->hasOne('app\admin\model\kaoshi\KaoshiSubject', 'id', 'subject_id')->setEagerlyType(0)->joinType('LEFT');
    }


    public function exams()
    {
        return $this->hasOne('app\admin\model\kaoshi\examination\KaoshiExams', 'id', 'exam_id')->setEagerlyType(0)->joinType('LEFT');
    }


}
