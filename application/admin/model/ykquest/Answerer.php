<?php

namespace app\admin\model\ykquest;

use think\Model;
use traits\model\SoftDelete;

class Answerer extends Model {

    use SoftDelete;

    // 表名
    protected $name = 'ykquest_answerer';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';
    // 追加属性
    protected $append = [
        'status_text'
    ];

    public function getStatusList() {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }

    public function getStatusTextAttr($value, $data) {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

}
