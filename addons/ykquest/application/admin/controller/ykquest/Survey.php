<?php

namespace app\admin\controller\ykquest;

use app\common\controller\Backend;
use app\admin\model\ykquest\Problem;
use think\Db;
use app\admin\model\Admin;
use app\admin\model\ykquest\Toption;

/**
 * 问卷管理
 *
 * @icon fa fa-circle-o
 */
class Survey extends Backend {

    /**
     * Survey模型对象
     * @var \app\admin\model\ykquest\Survey
     */
    protected $model = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = new \app\admin\model\ykquest\Survey;
        $this->view->assign("statusList", $this->model->getStatusList());
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
                    ->with(['surveytype'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['surveytype'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $adminModel = new Admin();
            foreach ($list as $row) {
                $info = $adminModel->where("id", $row['admin_id'])->Field("nickname")->find();
                if ($info) {
                    $row['admin_id'] = $info['nickname'];
                }
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
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
                $Problem = new Problem();
                $count = $Problem->where("survey_id", "in", $ids)->count();
                if ($count == 0) {
                    parent::del($ids);
                } else {
                    $this->error(__('Please delete the title first'));
                }
            }
            $this->error(__('No rows were deleted'));
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 添加
     */
    public function add() {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $params["admin_id"] = $this->auth->id;
                    $result = $this->model->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
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
     * 批量更新
     */
    public function multi($ids = "") {
        $ids = $ids ? $ids : $this->request->param("ids");
        if ($ids) {
            if ($this->request->has('params')) {
                parse_str($this->request->post("params"), $values);
                $values = array_intersect_key($values, array_flip(is_array($this->multiFields) ? $this->multiFields : explode(',', $this->multiFields)));
                if ($values || $this->auth->isSuperAdmin()) {
                    $adminIds = $this->getDataLimitAdminIds();
                    if (is_array($adminIds)) {
                        $this->model->where($this->dataLimitField, 'in', $adminIds);
                    }
                    $count = 0;
                    Db::startTrans();
                    try {
                        $list = $this->model->where($this->model->getPk(), 'in', $ids)->select();
                        $proModel = new Problem();

                        foreach ($list as $index => $item) {
                            if (isset($values['status']) && $values['status'] == 1) {
                                $temp = $proModel->where("survey_id", $item['id'])->count();
                                if ($temp == 0) {
                                    Db::rollback();
                                    $this->error(__('No problem set and cannot be opened'));
                                }
                            }
                            $count += $item->allowField(true)->isUpdate(true)->save($values);
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
                        $this->error(__('No rows were updated'));
                    }
                } else {
                    $this->error(__('You have no permission'));
                }
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 预览
     */
    public function detail($ids = null) {
        $row = $this->model->where("id", $ids)->find();
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $proModel = new Problem();
        $tpModel = new Toption();
        $list = $proModel->where("survey_id", $ids)->order("id asc,weigh desc")->select();
        $arr = [];
        foreach ($list as $val) {
            if ($val['option_type'] <= 1) {
                $val['oplist'] = $tpModel->where("problem_id", $val['id'])->select();
            }
            $arr[] = $val;
        }
        $this->assign("row", $row);
        $this->assign("list", $arr);
        return $this->view->fetch();
    }

}
