<?php

namespace addons\kaoshi\model\examination;

use think\Model;
use traits\model\SoftDelete;

class KaoshiExams extends Model
{

    use SoftDelete;

    // 表名
    protected $name = 'kaoshi_exams';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'type_text',
    ];

    public function getTypeList()
    {
        // return ['1' => __('Type 1'), '2' => __('Type 2')];
        return ['1' => __('Type 1')];
    }

    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function subject()
    {
        return $this->hasOne('app\admin\model\KaoshiSubject', 'id', 'subject_id')->setEagerlyType(0)->joinType('LEFT');
    }

    public function admin()
    {
        return $this->hasOne('app\admin\model\Admin', 'id', 'admin_id')->setEagerlyType(0)->joinType('LEFT');
    }

}
