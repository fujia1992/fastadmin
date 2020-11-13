<?php

namespace app\admin\model;

use think\Model;

class Workforce extends Model
{
    // 表名
    protected $name = 'recruit_workforce';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'sex_text',
        'education_text'
    ];
    

    
    public function getSexList()
    {
        return ['0' => __('Sex 0'),'1' => __('Sex 1')];
    }     

    public function getEducationList()
    {
        return ['0' => __('Education 0'),'1' => __('Education 1'),'2' => __('Education 2'),'3' => __('Education 3'),'4' => __('Education 4'),'5' => __('Education 5'),'6' => __('Education 6'),'7' => __('Education 7')];
    }     


    public function getSexTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['sex'];
        $list = $this->getSexList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getEducationTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['education'];
        $list = $this->getEducationList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
