<?php

namespace addons\vote\model;

use think\Model;


class Comment extends Model
{

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'vote_comment';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text'
    ];

    protected static $config = [];

    protected static function init()
    {
        $config = get_addon_config('vote');
        self::$config = $config;
    }

    public function getUrlAttr($value, $data)
    {
        return addon_url('vote/player/index', [':id' => $data['id']], static::$config['urlsuffix']);
    }

    public function getFullurlAttr($value, $data)
    {
        return addon_url('vote/player/index', [':id' => $data['id']], static::$config['urlsuffix'], true);
    }

    public function getRgbAttr($value, $data)
    {
        $value = $data['bgcolor'] ? $data['bgcolor'] : '#d0e100';
        $hex = substr($value, 0, 1) != '#' ? '#' . $value : $value;
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        return [$r, $g, $b];
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }

    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function user()
    {
        return $this->belongsTo('\app\common\model\User', 'user_id', 'id');
    }

}
