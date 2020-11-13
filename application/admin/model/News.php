<?php

namespace app\admin\model;

use think\Model;

class News extends Model
{

    // 表名
    protected $name = 'recruit_news';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
        'status_text',
        'baoming_text'
    ];

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }

    public function getBaomingList()
    {
        return ['news' => __('Baoming news'), 'baoming' => __('Baoming baoming')];
    }

    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : $data['status'];
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getBaomingTextAttr($value, $data)
    {
        $value = $value ? $value : $data['baoming'];
        $list = $this->getBaomingList();
        return isset($list[$value]) ? $list[$value] : '';
    }

}
