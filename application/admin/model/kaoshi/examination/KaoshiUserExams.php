<?php

namespace app\admin\model\kaoshi\examination;

use think\Model;


class KaoshiUserExams extends Model
{


    // 表名
    protected $name = 'kaoshi_user_exams';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text',
        'starttime_text',
        'lasttime_text'
    ];


    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStarttimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['starttime']) ? $data['starttime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getLasttimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['lasttime']) ? $data['lasttime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setStarttimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setLasttimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    public function exams()
    {
        return $this->hasOne('app\admin\model\kaoshi\examination\KaoshiExams', 'id', 'exams_id')->setEagerlyType(0)->joinType('LEFT');
    }


    public function user()
    {
        return $this->hasOne('app\admin\model\User', 'id', 'user_id')->setEagerlyType(0)->joinType('LEFT');
    }
}
