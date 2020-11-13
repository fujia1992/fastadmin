<?php

namespace app\admin\controller\ykquest;

use app\common\controller\Backend;
use app\admin\model\ykquest\Answerer;
use app\admin\model\ykquest\Survey;
use app\admin\model\ykquest\Problem;
use app\admin\model\ykquest\Toption;

/**
 * 答卷管理
 *
 * @icon fa fa-circle-o
 */
class Reply extends Backend {

    /**
     * Reply模型对象
     * @var \app\admin\model\ykquest\Reply
     */
    protected $model = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = new \app\admin\model\ykquest\Reply;
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
                    ->with(['problem'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['problem'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $ansModel = new Answerer();
            $surModel = new Survey();
            foreach ($list as $row) {
                $info = $ansModel->where("id", $row['answerer_id'])->find();
                $row['answerer_id'] = $info['nickname'];
                $surInfo = $surModel->where("id", $row['survey_id'])->find();
                $row['survey_id'] = $surInfo['name'];
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 答题详情
     */
    public function detail($ids = null) {
        $row = $this->model->where('id', $ids)->find();

        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $arr = [];
        $oplist = [];
        $content = $row['content'];
        $prombleId = $row['problem_id'];
        $proModel = new Problem();
        $info = $proModel->where("id", $prombleId)->Field("option_type,title")->find();
        if (!$info) {
            $this->error(__('No Results were found'));
        }
        if ($info) {
            $opModel = new Toption();
            if ($info['option_type'] <= 1) {
                $oplist = $opModel->where("problem_id", $prombleId)->select();
                if (!$oplist) {
                    $this->error(__('No Results were found'));
                }
                if ($info['option_type'] == 1) {
                    $content = json_decode($content, true);
                }
            }
        }
        $this->assign("info", $info);
        $this->assign("oplist", $oplist);
        $this->assign("content", $content);
        return $this->view->fetch();
    }

}
