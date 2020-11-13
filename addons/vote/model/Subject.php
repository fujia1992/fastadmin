<?php

namespace addons\vote\model;

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
        'status_text'
    ];
    protected static $rankList = null;

    protected static $config = [];

    protected static function init()
    {
        $config = get_addon_config('vote');
        self::$config = $config;

        $refresh = function ($subject_id) {
            $time = time();
            Fields::where('subject_id', $subject_id)->delete();
            $applyfield = request()->post("applyfield/a", []);
            $data = [];
            foreach ($applyfield as $index => $item) {
                unset($item['id']);
                $item['subject_id'] = $subject_id;
                $item['createtime'] = $time;
                $item['updatetime'] = $time;
                $item['decimals'] = 0;
                $item['length'] = 255;
                $item['minimum'] = 0;
                $item['msg'] = '';
                $item['ok'] = '';
                $item['status'] = 'normal';
                $data[] = $item;
            }
            if ($data) {
                (new Fields())->allowField(true)->insertAll($data);
                self::refresFields($subject_id);
            }
            Category::where('subject_id', $subject_id)->delete();
            $category = request()->post('category');
            $categoryArr = array_unique(array_filter(explode(',', $category)));
            $data = [];
            foreach ($categoryArr as $index => $item) {
                $data[] = [
                    'subject_id' => $subject_id,
                    'name'       => $item,
                    'createtime' => $time,
                    'updatetime' => $time,
                    'status'     => 'normal',
                ];
            }
            if ($data) {
                (new Category())->allowField(true)->insertAll($data);
            }
        };
        self::afterInsert(function ($row) use ($refresh) {
            $refresh($row['id']);
        });
        self::afterUpdate(function ($row) use ($refresh) {
            $refresh($row['id']);
        });
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden'), 'expired' => __('Expired')];
    }

    public function getUrlAttr($value, $data)
    {
        return addon_url('vote/subject/index', [':id' => $data['id'], ':diyname' => $data['diyname']], static::$config['urlsuffix']);
    }

    public function getFullurlAttr($value, $data)
    {
        return addon_url('vote/subject/index', [':id' => $data['id'], ':diyname' => $data['diyname']], static::$config['urlsuffix'], true);
    }

    public function getRankUrlAttr($value, $data)
    {
        return addon_url('vote/rank/index', [':id' => $data['id'], ':diyname' => $data['diyname']]);
    }

    public function getApplyUrlAttr($value, $data)
    {
        return addon_url('vote/apply/index', [':id' => $data['id'], ':diyname' => $data['diyname']]);
    }

    public function getBannerAttr($value, $data)
    {
        return $value ? $value : "/assets/addons/vote/img/banner.jpg";
    }

    public function getStatusAttr($value, $data)
    {
        $status = $value;
        $time = time();
        if ($value == 'normal') {
            if ($time < $data['begintime']) {
                $status = 'notstarted';
            } elseif ($time > $data['endtime']) {
                $status = 'expired';
            }
        }
        return $status;
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

    /**
     * 获取总排名数据
     * @param int $subject_id 投票主题ID
     * @return array|mixed
     */
    public static function getRankList($subject_id)
    {
        if (!isset(self::$rankList[$subject_id])) {
            return self::setRankList($subject_id);
        } else {
            return self::$rankList[$subject_id];
        }
    }

    /**
     * 设置排名数据
     * @param int $subject_id 投票主题ID
     * @return array|mixed
     */
    public static function setRankList($subject_id)
    {
        if (isset(self::$rankList[$subject_id])) {
            return self::$rankList[$subject_id];
        }
        $list = Player::where('subject_id', $subject_id)
            ->where('status', 'normal')
            //->cache('ranklist-' . $data['subject_id'])
            ->order('votes DESC,votetime ASC')
            ->column('id,category_id');
        self::$rankList[$subject_id] = $list;
        return $list;
    }

    public static function getSubjectFields($subject_id, $player = null)
    {
        $values = is_array($player['applydata']) ? $player['applydata'] : (array)json_decode($player['applydata'], true);
        $fields = Fields::where('subject_id', $subject_id)
            ->where('status', 'normal')
            ->order('weigh desc,id desc')
            ->select();
        foreach ($fields as $k => $v) {
            //优先取编辑的值,再次取默认值
            $v->value = isset($values[$v['name']]) ? $values[$v['name']] : (is_null($v['defaultvalue']) ? '' : $v['defaultvalue']);
            $v->rule = str_replace(',', '; ', $v->rule);
            if (in_array($v['type'], ['checkbox', 'lists', 'images'])) {
                $checked = '';
                if ($v['minimum'] && $v['maximum']) {
                    $checked = "{$v['minimum']}~{$v['maximum']}";
                } elseif ($v['minimum']) {
                    $checked = "{$v['minimum']}~";
                } elseif ($v['maximum']) {
                    $checked = "~{$v['maximum']}";
                }
                if ($checked) {
                    $v->rule .= (';checked(' . $checked . ')');
                }
            }
            if (in_array($v['type'], ['checkbox', 'radio']) && stripos($v->rule, 'required') !== false) {
                $v->rule = str_replace('required', 'checked', $v->rule);
            }
            if (in_array($v['type'], ['selects'])) {
                $v->extend .= (' ' . 'data-max-options="' . $v['maximum'] . '"');
            }
        }

        return $fields;
    }

    public function getCategoryAttr($value, $data)
    {
        $categoryList = Category::where('subject_id', $data['id'])->column('name');
        return implode(',', $categoryList);
    }

    /**
     * 读取配置类型
     * @return array
     */
    public static function getTypeList()
    {
        $typeList = [
            'string'   => __('String'),
            'text'     => __('Text'),
            'number'   => __('Number'),
            'datetime' => __('Datetime'),
            'select'   => __('Select'),
            'selects'  => __('Selects'),
            'image'    => __('Image'),
            'images'   => __('Images'),
            'file'     => __('File'),
            'files'    => __('Files'),
            'checkbox' => __('Checkbox'),
            'radio'    => __('Radio'),
            'array'    => __('Array'),
        ];
        return $typeList;
    }

    public function getApplyfieldListAttr($value, $data)
    {
        if (isset($this->data['applyfield_list'])) {
            return $this->data['applyfield_list'];
        }
        $fieldList = Fields::where('subject_id', $data['id'])->field('id,name,title,type,content,rule,defaultvalue')->select();
        $this->data['applyfield_list'] = collection($fieldList)->toArray();
        return $this->data['applyfield_list'];
    }

    public static function refresFields($subject_id)
    {
        $fields = Fields::where('subject_id', $subject_id)->field('name')->column('name');
        self::where('id', $subject_id)->update(['applyfields' => implode(',', $fields)]);
    }

}
