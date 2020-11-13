<?php

namespace app\admin\model\vote;

use think\Model;

class Category extends Model
{

    // 表名
    protected $name = 'vote_category';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
        'status_text',
    ];

    protected static $config = [];

    protected static function init()
    {
        $config = get_addon_config('vote');
        self::$config = $config;
        self::afterInsert(function ($row) {
            //创建时自动添加权重值
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });

        self::afterDelete(function ($row) {
            //删除时，删除子节点，同时将所有相关文档移入回收站
            static $tree;
            if (!$tree) {
                $tree = \fast\Tree::instance();
                $tree->init(collection(Category::order('weigh desc,id desc')->field('id,pid,name,status')->select())->toArray(), 'pid');
            }
            $childIds = $tree->getChildrenIds($row['id']);
            if ($childIds) {
                Category::destroy(function ($query) use ($childIds) {
                    $query->where('id', 'in', $childIds);
                });
            }
            $childIds[] = $row['id'];
        });
        self::afterWrite(function ($row) {
            $changed = $row->getChangedData();
            //隐藏时判断是否有子节点,有则隐藏
            if (isset($changed['status']) && $changed['status'] == 'hidden') {
                static $tree;
                if (!$tree) {
                    $tree = \fast\Tree::instance();
                    $tree->init(collection(Category::order('weigh desc,id desc')->field('id,pid,name,status')->select())->toArray(), 'pid');
                }
                $childIds = $tree->getChildrenIds($row['id']);
                db('vote_category')->where('id', 'in', $childIds)->update(['status' => 'hidden']);
            }
        });
    }

    public static function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }

    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : $data['status'];
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

}
