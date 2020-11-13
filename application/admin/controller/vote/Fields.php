<?php

namespace app\admin\controller\vote;

use app\common\controller\Backend;
use app\common\model\Config;
use think\Exception;

/**
 * 投票报名字段管理
 *
 * @icon fa fa-circle-o
 */
class Fields extends Backend
{

    /**
     * Subject模型对象
     * @var \app\admin\model\vote\Fields
     */
    protected $model = null;

    protected $noNeedRight = ['rulelist'];
    protected $multiFields = 'isfilter,iscontribute';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\vote\Fields;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign('typeList', Config::getTypeList());
        $this->view->assign('regexList', Config::getRegexList());
    }

    /**
     * 查看
     */
    public function index()
    {
        $subject_id = $this->request->param('subject_id', 0);
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where('subject_id', $subject_id)
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where('subject_id', $subject_id)
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->assignconfig('subject_id', $subject_id);
        $this->view->assign('subject_id', $subject_id);

        $subject = \app\admin\model\vote\Subject::get($subject_id);
        $this->view->assign('model', $subject);

        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        $subject_id = $this->request->param('subject_id', 0);
        $this->view->assign('subject_id', $subject_id);
        return parent::add();
    }

    /**
     * 批量操作
     * @param string $ids
     */
    public function multi($ids = "")
    {
        $params = $this->request->request('params');
        parse_str($params, $paramsArr);
        if (isset($paramsArr['isfilter'])) {
            $field = \app\admin\model\vote\Fields::get($ids);
            if (!$field || !in_array($field['type'], ['radio', 'checkbox', 'select', 'selects', 'array'])) {
                $this->error('只有类型为单选、复选、下拉列表、数组才可以加入列表筛选');
            }
        }
        return parent::multi($ids);
    }

    /**
     * 规则列表
     * @internal
     */
    public function rulelist()
    {
        //主键
        $primarykey = $this->request->request("keyField");
        //主键值
        $keyValue = $this->request->request("keyValue", "");

        $keyValueArr = array_filter(explode(',', $keyValue));
        $regexList = Config::getRegexList();
        $list = [];
        foreach ($regexList as $k => $v) {
            if ($keyValueArr) {
                if (in_array($k, $keyValueArr)) {
                    $list[] = ['id' => $k, 'name' => $v];
                }
            } else {
                $list[] = ['id' => $k, 'name' => $v];
            }
        }
        return json(['list' => $list]);
    }

}
