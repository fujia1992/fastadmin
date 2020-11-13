<?php

namespace app\admin\model\cms;

use think\Model;

class Tags extends Model
{

    // 表名
    protected $name = 'cms_tags';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    // 追加属性
    protected $append = [
        'url'
    ];
    protected static $config = [];

    protected static function init()
    {
        static::$config = get_addon_config('cms');
    }

    public function getUrlAttr($value, $data)
    {
        return addon_url('cms/tags/index', [':name' => $data['name']], static::$config['urlsuffix']);
    }

    public function getFullurlAttr($value, $data)
    {
        return addon_url('cms/tags/index', [':name' => $data['name']], static::$config['urlsuffix'], true);
    }
}
