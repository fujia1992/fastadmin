<?php

namespace app\admin\controller\ykquest;

use app\common\controller\Backend;
use app\admin\model\ykquest\Toption;
use app\admin\model\ykquest\Reply;
use think\Db;

/**
 * 问题管理
 *
 * @icon fa fa-circle-o
 */
class Problem extends Backend {

    /**
     * Problem模型对象
     * @var \app\admin\model\ykquest\Problem
     */
    protected $model = null;
    protected $singleArr = [0, 1, 2];

    public function _initialize() {
        parent::_initialize();
        $this->model = new \app\admin\model\ykquest\Problem;
        $this->view->assign("optionTypeList", $this->model->getOptionTypeList());
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

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
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['survey'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add() {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $allParams = $params;
                $tempArr = array();
                $option_type = isset($params['option_type']) && $params['option_type'] != NULL ? $params['option_type'] : "";
                if ($option_type == "") {
                    $this->error(__('Type cannot be empty'));
                }
                $this->model->startTrans();
                $nowTime = time();
                foreach ($this->model->getOptionTypeList() as $key => $val) {
                    unset($allParams['toption' . $key]);
                }
                $allParams['admin_id'] = $this->auth->id;
                $allParams['createtime'] = $nowTime;
                $allParams['updatetime'] = $nowTime;
                if (true) {
                    $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                    $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                    $this->model->validateFailException(true)->validate($validate);
                }
                $problem_id = $this->model->allowField(true)->insertGetId($allParams);
                if (!$problem_id) {
                    $this->model->rollback();
                    $this->error(__('No rows were inserted'));
                }
                $arr = $this->singleArr;
                if (in_array($option_type, $arr)) {
                    if (!isset($params['toption' . $option_type])) {
                        $this->model->rollback();
                        $this->error(__('Option cannot be empty'));
                    }
                    $option_list = $this->model->object_array(json_decode($params['toption' . $option_type]));
                    foreach ($option_list as $val) {
                        if ($val['content'] == "") {
                            $this->model->rollback();
                            $this->error(__('Option value cannot be empty'));
                        }
                        $tempArr[] = array(
                            "content" => $val['content'],
                            "problem_id" => $problem_id,
                            "createtime" => $nowTime,
                            "updatetime" => $nowTime,
                        );
                    }
                    if (count($tempArr) < 2) {
                        $this->model->rollback();
                        $this->error(__('No less than two options'));
                    }
                    $toption = new Toption();
                    $toption->startTrans();
                    $insert = $toption->insertAll($tempArr);
                    if ($insert) {
                        $this->model->commit();
                        $toption->commit();
                        $this->success();
                    }
                    $toption->rollback();
                    $this->model->rollback();
                    $this->error(__('No rows were inserted'));
                }
                $this->model->commit();
                $this->success();
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null) {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $option_list = array();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }

        if (in_array($row['option_type'], $this->singleArr)) {
            $toption = new Toption();
            $option_list = $toption->where("problem_id", $row['id'])->select();
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $allParams = $params;
                $tempArr = array();
                $option_type = isset($params['option_type']) && $params['option_type'] != NULL ? $params['option_type'] : "";
                if ($option_type == "") {
                    $this->error(__('Type cannot be empty'));
                }
                $this->model->startTrans();
                $nowTime = time();
                foreach ($this->model->getOptionTypeList() as $key => $val) {
                    unset($allParams['toption' . $key]);
                }
                $allParams['updatetime'] = $nowTime;
                if (true) {
                    $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                    $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                    $this->model->validateFailException(true)->validate($validate);
                }
                $update = $this->model->allowField(true)->where("id", $ids)->update($allParams);
                if (!$update) {
                    $this->model->rollback();
                    $this->error(__('No rows were updated'));
                }
                $arr = $this->singleArr;
                $toption = new Toption();
                if (in_array($option_type, $arr)) {
                    if (!isset($params['toption' . $option_type])) {
                        $this->model->rollback();
                        $this->error(__('Option cannot be empty'));
                    }
                    $toption->startTrans();
                    $option_list = $this->model->object_array(json_decode($params['toption' . $option_type]));
                    foreach ($option_list as $val) {
                        if ($val['content'] == "") {
                            $toption->rollback();
                            $this->model->rollback();
                            $this->error(__('Option value cannot be empty'));
                        }
                        if ($val['intro'] != "" && $val['intro'] != NULL && $val['intro'] > 0) {
                            $data = array(
                                "content" => $val['content'],
                                "updatetime" => $nowTime,
                            );
                            $up = $toption->where("id", $val['intro'])->where("problem_id", $ids)->update($data);
                            if (!$up) {
                                $toption->rollback();
                                $this->model->rollback();
                                $this->error(__('No rows were updated'));
                            }
                            $tempArr[] = $val['intro'];
                        } else {
                            $tdata = array(
                                "content" => $val['content'],
                                "problem_id" => $ids,
                                "createtime" => $nowTime,
                                "updatetime" => $nowTime,
                            );
                            $proId = $toption->insertGetId($tdata);
                            if (!$proId) {
                                $toption->rollback();
                                $this->model->rollback();
                                $this->error(__('No rows were updated'));
                            } else {
                                $tempArr[] = $proId;
                            }
                        }
                    }
                    if (count($tempArr) < 2) {
                        $this->model->rollback();
                        $toption->rollback();
                        $this->error(__('No less than two options'));
                    }
                    $deletTime['deletetime'] = time();
                    $toption->where("problem_id", $ids)->where("id", "not in", $tempArr)->update($deletTime);
                    $this->model->commit();
                    $toption->commit();
                    $this->success();
                } else {
                    $this->model->commit();
                    $toption->commit();
                    $this->success();
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        $this->view->assign("list", $option_list);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "") {
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();
            $repkyModel = new Reply();
            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $tempCount = $repkyModel->where("problem_id", $v['id'])->count();
                    if ($tempCount > 0) {
                        $this->error(__('Cannot delete existing answers'));
                    }
                    $count += $v->delete();
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

}
