<?php

namespace addons\kaoshi\model\examination;

use think\Model;

class KaoshiUserPlan extends Model
{

    // 表名
    protected $name = 'kaoshi_user_plan';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text',
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

    public function user()
    {
        return $this->hasOne('app\admin\model\User', 'id', 'user_id')->setEagerlyType(0)->joinType('LEFT');
    }

    public function plan()
    {
        return $this->hasOne('addons\kaoshi\model\examination\KaoshiPlan', 'id', 'plan_id')->setEagerlyType(0)->joinType('LEFT');
    }

}
