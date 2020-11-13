<?php

namespace app\admin\model;

use think\Model;

class Resumedelivery extends Model
{
    // 表名
    protected $name = 'recruit_resumedelivery';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [

    ];
    

    







    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function job()
    {
        return $this->belongsTo('Job', 'job_id', 'Id', [], 'LEFT')->setEagerlyType(0);
    }


    public function resume()
    {
        return $this->belongsTo('Resume', 're_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
