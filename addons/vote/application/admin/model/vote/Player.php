<?php

namespace app\admin\model\vote;

use think\Model;


class Player extends Model
{

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'vote_player';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'votetime_text',
        'status_text',
        'url'
    ];

    protected static $config = [];

    protected static function init()
    {
        $config = get_addon_config('vote');
        self::$config = $config;
        self::beforeInsert(function ($row) {
            $number = Player::where('subject_id', $row->subject_id)->max('number');
            $number = $number ? $number : 0;
            $row->number = $number + 1;
        });
        self::afterWrite(function ($row) {
            Subject::refreshPlayers($row['subject_id']);
        });
        self::afterDelete(function ($row) {
            Subject::refreshPlayers($row['subject_id']);
        });
    }

    public function getUrlAttr($value, $data)
    {
        return addon_url('vote/player/index', [':id' => $data['id']], static::$config['urlsuffix']);
    }

    public function getFullurlAttr($value, $data)
    {
        return addon_url('vote/player/index', [':id' => $data['id']], static::$config['urlsuffix'], true);
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden'), 'rejected' => __('Rejected')];
    }

    public function getVotetimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['votetime']) ? $data['votetime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setVotetimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    public function user()
    {
        return $this->belongsTo('\app\common\model\User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function subject()
    {
        return $this->belongsTo('\app\admin\model\vote\Subject', 'subject_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function category()
    {
        return $this->belongsTo('\app\admin\model\vote\Category', 'category_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
