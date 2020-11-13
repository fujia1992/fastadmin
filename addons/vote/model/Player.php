<?php

namespace addons\vote\model;

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
        'status_text'
    ];

    protected $type = [
        'applydata' => 'json'
    ];

    protected static $config = [];

    protected static function init()
    {
        $config = get_addon_config('vote');
        self::$config = $config;
    }

    /**
     * 批量设置数据
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        if (!$data) {
            return $this;
        }
        if (is_object($data)) {
            $data = get_object_vars($data);
        }
        $this->data = array_merge($this->data, is_array($data) ? $data : []);
        return $this;
    }

    public function getUrlAttr($value, $data)
    {
        return addon_url('vote/player/index', [':id' => $data['id']], static::$config['urlsuffix']);
    }

    public function getFullurlAttr($value, $data)
    {
        return addon_url('vote/player/index', [':id' => $data['id']], static::$config['urlsuffix'], true);
    }

    public function getApplydataAttr($value, $data)
    {
        if (is_array($data['applydata'])) {
            return $data['applydata'];
        }
        return (array)json_decode($data['applydata'], true);
    }

    public function getRgbAttr($value, $data)
    {
        $value = $data['bgcolor'] ? $data['bgcolor'] : '#000000';
        $hex = substr($value, 0, 1) != '#' ? '#' . $value : $value;
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        return [$r, $g, $b];
    }

    public function getPercent($votes)
    {
        if (!$this->votes || !$votes) {
            return 0;
        }
        return round($this->votes / $votes * 100, 2);
    }

    public function getBannerAttr($value, $data)
    {
        return $value ? $value : "/assets/addons/vote/img/banner.jpg";
    }

    /**
     * 获取参赛排名
     */
    public function getRankAttr($value, $data)
    {
        $rankList = Subject::getRankList($data['subject_id']);
        $index = array_search($data['id'], array_keys($rankList));
        if ($index === false) {
            return 0;
        } else {
            return $index + 1;
        }
    }

    /**
     * 获取参赛分类排名
     */
    public function getCategoryRankAttr($value, $data)
    {
        $rankList = Subject::getRankList($data['subject_id']);
        $categoryArr = [];
        foreach ($rankList as $index => $item) {
            if ($item == $data['category_id']) {
                $categoryArr[] = $index;
            }
        }
        $index = array_search($data['id'], $categoryArr);
        if ($index === false) {
            return 0;
        } else {
            return $index + 1;
        }
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

    public function subject()
    {
        return $this->belongsTo('\addons\vote\model\Subject', 'subject_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
