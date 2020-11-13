<?php

namespace app\admin\model\cms;

use think\Config;
use think\Model;

class Modelx extends Model
{

    // 表名
    protected $name = 'cms_model';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
    ];

    public static function init()
    {
        self::beforeInsert(function ($row) {
            $row['setting'] = '{"contibutefields":["image","tags","content"]}';
        });
        self::afterInsert(function ($row) {
            $prefix = Config::get('database.prefix');
            $sql = "CREATE TABLE `{$prefix}{$row['table']}` (`id` int(10) NOT NULL,`content` longtext NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='{$row['name']}'";
            db()->query($sql);
        });
    }

    public function getFieldsAttr($value, $data)
    {
        return is_array($value) ? $value : ($value ? explode(',', $value) : []);
    }

    public function getSettingAttr($value, $data)
    {
        return is_array($value) ? $value : (array)json_decode($data['setting'], true);
    }

    public function setSettingAttr($value)
    {
        return is_array($value) ? json_encode($value) : $value;
    }
}
