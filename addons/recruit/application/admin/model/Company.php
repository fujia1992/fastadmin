<?php

namespace app\admin\model;

use think\Model;

class Company extends Model
{
    // 表名
    protected $name = 'recruit_company';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'xinzhi_text'
    ];
    

    
    public function getXinzhiList()
    {
        return ['0' => __('Xinzhi 0'),'1' => __('Xinzhi 1'),'2' => __('Xinzhi 2'),'3' => __('Xinzhi 3'),'4' => __('Xinzhi 4')];
    }     


    public function getXinzhiTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['xinzhi'];
        $list = $this->getXinzhiList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


}
