<?php

namespace app\admin\model\ykquest;

use think\Model;
use traits\model\SoftDelete;

class Detail extends Model {

    use SoftDelete;

    // 表名
    protected $name = 'ykquest_problem';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';
    // 追加属性
    protected $append = [
        'option_type_text'
    ];

    protected static function init() {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    public function getOptionTypeList() {
        return ['0' => __('Option_type 0'), '1' => __('Option_type 1'), '2' => __('Option_type 2'), '3' => __('Option_type 3')];
    }

    public function getOptionTypeTextAttr($value, $data) {
        $value = $value ? $value : (isset($data['option_type']) ? $data['option_type'] : '');
        $list = $this->getOptionTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function survey() {
        return $this->belongsTo('Survey', 'survey_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
