<?php

namespace app\admin\controller\kaoshi\examination;

use think\Db;
use app\common\controller\Backend;

/**
 * 考场管理
 *
 * @icon fa fa-circle-o
 */
class Plan extends Backend
{

    /**
     * Plan模型对象
     * @var \app\admin\model\kaoshi\examination\KaoshiPlan
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\examination\KaoshiPlan;
        $this->view->assign("typeList", $this->model->getTypeList());
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    /**
     * 查看
     */
    public function index()
    {
        $this->dataLimit = false;
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
                ->with(['subject', 'exams'])
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['subject', 'exams'])
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

    public function getplan($map)
    {
        $user_plan = new \app\admin\model\kaoshi\examination\KaoshiUserPlan;

        $field = "a.id, a.plan_name,a.starttime, a.endtime, a.type, c.exam_name, d.subject_name,count(b.id) as student_num";
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();

        $total = $this->model
            ->alias('a')
            ->field($field)
            ->where($map)
            ->join('__KAOSHI_USER_PLAN__ b', 'b.plan_id = a.id', 'left')
            ->join('__KAOSHI_EXAMS__ c', 'a.exam_id = c.id')
            ->join('__KAOSHI_SUBJECT__ d', 'c.subject_id = d.id')
            // ->order($sort, $order)
            ->limit($offset, $limit)
            ->group('a.id')
            ->count();;

        $list = $this->model
            ->alias('a')
            ->where($map)
            ->field($field)
            ->join('__KAOSHI_USER_PLAN__ b', 'b.plan_id = a.id', 'left')
            ->join('__KAOSHI_EXAMS__ c', 'a.exam_id = c.id')
            ->join('__KAOSHI_SUBJECT__ d', 'c.subject_id = d.id')
            // ->order($sort, $order)
            ->limit($offset, $limit)
            ->group('a.id')
            ->select();
        $list = collection($list)->toArray();
        foreach ($list as $key => $value) {
            $plan_id = $value['id'];
            $list[$key]['real_num'] = Db::name('KaoshiUserExams')->alias('a')->join('__KAOSHI_USER_PLAN__ b', 'b.id = a.user_plan_id')->where('b.plan_id = ' . $plan_id)->group('a.user_plan_id')->count();
        }
        $result = array("total" => $total, "rows" => $list);
        return $result;
    }

    /**
     * 查看学习计划
     */
    public function study()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            $map = [
                'a.type' => 1,
                'a.deletetime' => NULL,
            ];


            return json($this->getplan($map));
        }
        return $this->view->fetch();
    }


    public function exam()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            $plan = new \app\admin\model\kaoshi\examination\KaoshiPlan;

            $map = [
                'a.type' => 0,
                'a.deletetime' => NULL,
            ];
            return json($this->getplan($map));
        }
        return $this->view->fetch();

    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }

                $result = false;
                $map['endtime'] = ['>', time()];
                $map['deletetime'] = NULL;
                $map['type'] = ['=', $params['type']];
                $map['subject_id'] = ['=', $params['subject_id']];
                $map['exam_id'] = ['=', $params['exam_id']];
                $exam_row = Db::name('KaoshiExams')->where('id = ' . $params['exam_id'])->find();
                if ($params['subject_id'] != $exam_row['subject_id']) {
                    $this->error('考卷与科目不匹配，请重新选择');
                }

                $namelist = $this->model->where($map)->column('plan_name');

                if (in_array($params['plan_name'], $namelist)) {
                    $this->error('该名称已存在，且暂未结束');
                }

                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);


                    $plan_id = $this->model->id;
                    $userplan = [];
                    $user_ids = explode(',', $params['user_ids']);
                    if (count($user_ids) < 1) {
                        $this->error('请选择参与的学生！');
                    }
                    foreach ($user_ids as $key => $value) {
                        array_push($userplan, ['plan_id' => $plan_id, 'user_id' => $value]);
                    }
                    $result = Db::name('KaoshiUserPlan')->insertAll($userplan);


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
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $user_exams_obj = new \app\admin\model\kaoshi\examination\KaoshiUserExams;
            $user_exams = $user_exams_obj->alias('a')->where(['b.plan_id' => $ids])->join('__KAOSHI_USER_PLAN__ b', 'b.id = a.user_plan_id', 'left')->count();

            if ($row['starttime'] < time() && $user_exams > 0) {
                $this->error('已开始进行，无法修改');

            }

            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $exam_row = Db::name('KaoshiExams')->where('id = ' . $params['exam_id'])->find();
                if ($params['subject_id'] != $exam_row['subject_id']) {
                    $this->error('考卷与科目不匹配，请重新选择');

                }
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);

                    $plan_id = $ids;
                    $userplan = [];
                    $user_ids = explode(',', $params['user_ids']);
                    if (count($user_ids) < 1) {
                        $this->error('请选择参与的学生！');
                    }
                    Db::name('KaoshiUserPlan')->where('plan_id', $plan_id)->delete();
                    foreach ($user_ids as $key => $value) {
                        array_push($userplan, ['plan_id' => $plan_id, 'user_id' => $value]);
                    }
                    $result = Db::name('KaoshiUserPlan')->insertAll($userplan);


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
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $row['user_ids'] = implode(',', Db::name('kaoshi_user_plan')->where('plan_id', $row['id'])->column('user_id'));
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();
            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $count += $v->delete();
                }
                $userplan = Db::name('kaoshi_user_plan')->where('plan_id', 'in', $ids)->delete();
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
