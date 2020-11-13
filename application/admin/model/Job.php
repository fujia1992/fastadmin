<?php

namespace app\admin\model;

use think\Model;

class Job extends Model
{
    // 表名
    protected $name = 'recruit_job';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'age_text',
        'stay_text',
        'food_text',
        'safe_text',
        'education_text'
    ];
    

    
    public function getAgeList()
    {
        return ['0' => __('Age 0'),'1' => __('Age 1'),'2' => __('Age 2'),'3' => __('Age 3'),'4' => __('Age 4')];
    }     

    public function getStayList()
    {
        return ['0' => __('Stay 0'),'1' => __('Stay 1'),'2' => __('Stay 2')];
    }     

    public function getFoodList()
    {
        return ['0' => __('Food 0'),'1' => __('Food 1'),'2' => __('Food 2'),'3' => __('Food 3')];
    }     

    public function getSafeList()
    {
        return ['0' => __('Safe 0'),'1' => __('Safe 1'),'2' => __('Safe 2'),'3' => __('Safe 3')];
    }     

    public function getEducationList()
    {
        return ['0' => __('Education 0'),'1' => __('Education 1'),'2' => __('Education 2'),'3' => __('Education 3'),'4' => __('Education 4'),'5' => __('Education 5'),'6' => __('Education 6')];
    }     


    public function getAgeTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['age'];
        $list = $this->getAgeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStayTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['stay'];
        $list = $this->getStayList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getFoodTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['food'];
        $list = $this->getFoodList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getSafeTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['safe'];
        $list = $this->getSafeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getEducationTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['education'];
        $list = $this->getEducationList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function recruitcompany()
    {
        return $this->belongsTo('Company', 'c_id', 'Id', [], 'LEFT')->setEagerlyType(0);
    }


    public function recruitopencity()
    {
        return $this->belongsTo('Opencity', 'city_id', 'Id', [], 'LEFT')->setEagerlyType(0);
    }


    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
