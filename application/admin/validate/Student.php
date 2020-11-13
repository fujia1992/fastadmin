<?php

namespace app\admin\validate;

use think\Validate;

class Student extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'name' => 'require|unique:student',
        'phone' => 'require|unique:student',
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['name','phone'],
        'edit' => [],
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'name' => __('name'),
            'phone' => __('phone'),
        ];

        parent::__construct($rules, $message, $field);
    }
    
}
