<?php

namespace app\admin\model\vote;

use think\Model;

class Subject extends Model
{

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'vote_subject';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'begintime_text',
        'endtime_text',
        'status_text',
        'url'
    ];

    protected static $config = [];

    protected static function init()
    {
        $config = get_addon_config('vote');
        self::$config = $config;
    }

    public function getUrlAttr($value, $data)
    {
        return addon_url('vote/subject/index', [':diyname' => $data['diyname']], static::$config['urlsuffix']);
    }

    public function getFullurlAttr($value, $data)
    {
        return addon_url('vote/subject/index', [':diyname' => $data['diyname']], static::$config['urlsuffix'], true);
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden'), 'expired' => __('Expired'), 'rejected' => __('Rejected')];
    }


    public function getBegintimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['begintime']) ? $data['begintime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getEndtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['endtime']) ? $data['endtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setBegintimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setEndtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    public static function refreshPlayers($subject_id)
    {
        $players = Player::where('subject_id', $subject_id)->where('status', 'normal')->count();
        self::where('id', $subject_id)->update(['players' => $players]);
    }

    public static function refresFields($subject_id)
    {
        $fields = Fields::where('subject_id', $subject_id)->field('name')->column('name');
        self::where('id', $subject_id)->update(['applyfields' => implode(',', $fields)]);
    }
}
