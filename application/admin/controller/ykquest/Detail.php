<?php

namespace app\admin\controller\ykquest;

use app\common\controller\Backend;
use app\admin\model\ykquest\Reply;

/**
 * 详细统计
 *
 * @icon fa fa-circle-o
 */
class Detail extends Backend {

    /**
     * Detail模型对象
     * @var \app\admin\model\ykquest\Detail
     */
    protected $model = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = new \app\admin\model\ykquest\Detail;
        $this->view->assign("optionTypeList", $this->model->getOptionTypeList());
    }

    /**
     * 查看
     */
    public function index() {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(['survey'])
                    ->where($where)
                    ->where("survey.starttime", "<=", time())
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['survey'])
                    ->where($where)
                    ->where("survey.starttime", "<=", time())
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $arr = [];
            $allAcount = 0;
            $reply = new Reply();
            foreach ($list as $row) {
                $temmCount = $reply->where("problem_id", $row['id'])->count();
                $allAcount += $temmCount;
                $row['count'] = $temmCount;
                $arr[] = $row;
            }
            $lists = collection($arr)->toArray();
            $result = array("total" => $total, "rows" => $lists);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 文本题展示
     */
    public function detail2($ids = null) {
        $row = $this->model->where("id", $ids)->find();
        if (!$row) {
            $this->error(__('No Results were found'));
        }
//        var_dump($row['option_type']);exit;
        if ($row['option_type'] > 1) {

            if ($this->request->isAjax()) {
                $this->model = new \app\admin\model\ykquest\Reply;
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                $list = $this->model
                        ->where($where)
                        ->order($sort, $order)
                        ->limit($offset, $limit)
                        ->where("problem_id", $ids)
                        ->select();

                $total = $this->model
                        ->where($where)
                        ->order($sort, $order)
                        ->where("problem_id", $ids)
                        ->count();
                $result = array("total" => $total, "rows" => $list);
                return json($result);
            }
        }
        $this->assign("ids", $ids);
        return $this->view->fetch();
    }

    /**
     * 单选或者多选题统计
     */
    public function detail1($ids = null) {
        $row = $this->model->where("id", $ids)->find();
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($row['option_type'] <= 1) {
            if ($this->request->isAjax()) {

                $this->model = new \app\admin\model\ykquest\Toption;
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                $total = $this->model
                        ->where($where)
                        ->order($sort, $order)
                        ->where("problem_id", $ids)
                        ->count();
                $list = $this->model
                        ->where($where)
                        ->order($sort, $order)
                        ->limit($offset, $limit)
                        ->where("problem_id", $ids)
                        ->select();

                $model = new \app\admin\model\ykquest\Reply;
                $taotalCount = $model->where("problem_id", $ids)->count();
                $arr = [];

                foreach ($list as $val) {
                    if ($taotalCount > 0) {
                        $count = $model->where("content", "like", "%" . $val['id'] . "%")->where("problem_id", $ids)->count();
                        $val['count'] = $count;
                        $val['bl'] = round($count / $taotalCount * 100, 2);
                    } else {
                        $val['count'] = 0;
                        $val['bl'] = 0;
                    }
                    $arr[] = $val;
                }
                $result = array("total" => $total, "rows" => $arr);
                return json($result);
            }
        }
        $this->assign("ids", $ids);
        return $this->view->fetch();
    }

}
