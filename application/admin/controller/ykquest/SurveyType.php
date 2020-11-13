<?php

namespace app\admin\controller\ykquest;

use app\common\controller\Backend;
use app\admin\model\ykquest\Problem;
use app\admin\model\ykquest\Survey;

/**
 * 问卷类型管理
 *
 * @icon fa fa-circle-o
 */
class SurveyType extends Backend {

    /**
     * SurveyType模型对象
     * @var \app\admin\model\ykquest\SurveyType
     */
    protected $model = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = new \app\admin\model\ykquest\SurveyType;
    }

    /**
     * 添加
     */
    public function add() {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params['admin_id'] = $this->auth->id;
                $result = $this->model->allowField(true)->save($params);
                if ($result) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "") {
        if ($ids) {
            $list = $this->model->where("id", 'in', $ids)->select();
            if ($list) {
                $Survey = new Survey();
                $count = $Survey->where("type_id", "in", $ids)->count();
                if ($count == 0) {
                    parent::del($ids);
                } else {
                    $this->error(__('Delete question first'));
                }
            }
            $this->error(__('No rows were deleted'));
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

}
