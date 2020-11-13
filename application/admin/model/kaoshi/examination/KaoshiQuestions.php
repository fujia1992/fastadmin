<?php

namespace app\admin\model\kaoshi\examination;

use think\Model;
use traits\model\SoftDelete;

class KaoshiQuestions extends Model
{

    use SoftDelete;


    // 表名
    protected $name = 'kaoshi_questions';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'type_text',
        'level_text'
    ];


    public function getTypeList()
    {
        return ['1' => __('Type 1'), '2' => __('Type 2'), '4' => __('Type 4'), '5' => __('Type 5')];
    }

    public function getLevelList()
    {
        return ['1' => __('Level 1'), '2' => __('Level 2'), '3' => __('Level 3')];
    }

    public function getStatusList()
    {
        return ['1' => __('Status 1'), '2' => __('Status 2')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getLevelTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['level']) ? $data['level'] : '');
        $list = $this->getLevelList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function subject()
    {
        return $this->hasOne('app\admin\model\kaoshi\KaoshiSubject', 'id', 'subject_id')->setEagerlyType(0)->joinType('LEFT');
    }


    public function admin()
    {
        return $this->hasOne('app\admin\model\Admin', 'id', 'admin_id')->setEagerlyType(0)->joinType('LEFT');
    }


}
