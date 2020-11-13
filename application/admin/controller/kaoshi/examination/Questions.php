<?php

namespace app\admin\controller\kaoshi\examination;

use app\common\controller\Backend;
use think\Db;

/**
 * 试题管理
 *
 * @icon fa fa-circle-o
 */
class Questions extends Backend
{

    /**
     * Questions模型对象
     * @var \app\admin\model\kaoshi\examination\KaoshiQuestions
     */
    protected $model = null;
    protected $dataLimit = 'auth';
    protected $dataLimitField = 'admin_id';


    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\examination\KaoshiQuestions;
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("levelList", $this->model->getLevelList());
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
                ->with(['subject', 'admin'])
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['subject', 'admin'])
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
     * 考卷关联题目
     */
    public function choosesubject($subjectid = null)
    {
        $this->dataLimit = false;
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if(!$subjectid){
            $this->error('参数有误');
        }
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();
        if ($this->request->isAjax()) {
            $total = $this->model
                ->with(['subject', 'admin'])
                ->where($where)
                ->where("kaoshi_questions.subject_id",$subjectid)
                ->where("kaoshi_questions.status",'1')
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['subject', 'admin'])
                ->where($where)
                ->where("kaoshi_questions.subject_id",$subjectid)
                ->where("kaoshi_questions.status",'1')
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
    public function add()
    {
        $selectdata = [
            [
                ['key' => 'A', 'value' => ''],
                ['key' => 'B', 'value' => ''],
                ['key' => 'C', 'value' => ''],
                ['key' => 'D', 'value' => ''],
            ],
            [
                ['key' => 'A', 'value' => ''],
                ['key' => 'B', 'value' => ''],
                ['key' => 'C', 'value' => ''],
                ['key' => 'D', 'value' => ''],

            ],
            [
                ['key' => 'A', 'value' => '对'],
                ['key' => 'B', 'value' => '错'],
            ],
            [
                ['key' => '', 'value' => ''],
            ],
            [
                ['key' => '', 'value' => ''],
            ],
        ];
        $this->view->assign("selectdata", $selectdata);
        if ($this->request->isPost()) {

            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                //echo"<pre>";print_r($params);
                $type = intval($params['type']) - 1;
                $params['selectdata'] = $params['selectdata' . $type];
                $selectarr = json_decode($params['selectdata'], true);
                $params['selectnumber'] = count($selectarr);
                if (!array_key_exists('answer' . $type, $params)) {
                    $this->error("请选择正确答案!");
                }
                $params['answer'] = $params['answer' . $type];

                if($type != '4'){
                    foreach ($selectarr as $key => $value) {
                        if (empty($value['key']) && $value['key'] != '0') {
                            $this->error("请填写选项" . ($key + 1));
                        }
                        if (empty($value['value']) && $value['value'] != '0') {
                            $this->error("请填写选项" . ($key + 1) . "答案内容");
                        }
                    }
                }


                if (count(array_unique(array_map('strtolower', array_column($selectarr, 'key')))) != count($selectarr)) {
                    $this->error("请不要输入重复选项!【选项不区分大小写】");
                }
                if (count(array_unique(array_column($selectarr, 'value'))) != count($selectarr)) {
                    $this->error("请不要输入重复选项答案!");

                }
                if($type == '3'){
                    foreach ($params['answer'] as $k => $v) {
                        if (empty($v)) {
                            $this->error("请填写选项" . ($k + 1) . "答案内容");
                        }
                    }
                    $params['selectnumber'] = count($params['answer']);
                }
                if ($type == '1' || $type == '3') {
                    $params['answer'] = implode(',', $params['answer']);
                }

                if (empty($params['answer']) && $params['answer'] != '0') {
                    $this->error("请选择正确答案!");
                }
                if($type == '4'){
                    $params['answer'] = '';
                    $params['selectdata'] = '[]';
                    $params['selectnumber'] = '0';
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
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $type = intval($params['type']) - 1;
                $params['selectdata'] = $params['selectdata' . $type];
                $selectarr = json_decode($params['selectdata'], true);
                $params['selectnumber'] = count($selectarr);
                if(empty($params['answer' . $type])){
                    $this->error("请选择答案");
                }
                $params['answer'] = $params['answer' . $type];

                if($type < '3') {
                    foreach ($selectarr as $key => $value) {
                        if (empty($value['key']) && $value['key'] != '0') {
                            $this->error("请填写选项" . ($key + 1));
                        }
                        if (empty($value['value']) && $value['value'] != '0') {
                            $this->error("请填写选项" . ($key + 1) . "答案内容");
                        }
                        unset($selectarr[$key]['checked']);
                    }
                    $params['selectdata'] = json_encode($selectarr);

                    if (count(array_unique(array_map('strtolower', array_column($selectarr, 'key')))) != count($selectarr)) {
                        $this->error("请不要输入重复选项!【选项不区分大小写】");
                    }
                    if (count(array_unique(array_column($selectarr, 'value'))) != count($selectarr)) {
                        $this->error("请不要输入重复选项答案!");
                    }
                }

                if($type == '3'){
                    foreach ($params['answer'] as $k => $v) {
                        if (empty($v)) {
                            $this->error("请填写选项" . ($k + 1) . "答案内容");
                        }
                    }
                    $params['selectnumber'] = count($params['answer']);
                    $params['selectdata'] = '[]';
                }
                if ($type == '1' || $type == '3') {
                    $params['answer'] = implode(',', $params['answer']);
                }


                if (empty($params['answer']) && $params['answer'] != '0') {
                    $this->error("请选择正确答案!");
                }

                if($type == '4'){
                    $params['answer'] = '';
                    $params['selectdata'] = '[]';
                    $params['selectnumber'] = '0';
                }
                $result = false;
                //"<pre>";print_r($params);exit;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
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

        $selectdata = [
            [
                ['key' => 'A', 'value' => ''],
                ['key' => 'B', 'value' => ''],
                ['key' => 'C', 'value' => ''],
                ['key' => 'D', 'value' => ''],
            ],
            [
                ['key' => 'A', 'value' => ''],
                ['key' => 'B', 'value' => ''],
                ['key' => 'C', 'value' => ''],
                ['key' => 'D', 'value' => ''],

            ],
            [
                ['key' => 'A', 'value' => '对'],
                ['key' => 'B', 'value' => '错'],

            ],
            [
                ['key' => '', 'value' => ''],
            ],
            [
                ['key' => '', 'value' => ''],
            ],
        ];
        $select_arr = json_decode($row['selectdata'], true);
        $row['answer'] = explode(',', $row['answer']);
        if($row['type'] < '4') {
            foreach ($select_arr as $key => $value) {
                $select_arr[$key]['checked'] = in_array($value['key'], $row['answer']) ? "checked" : "";
            }
        }
        if (count($select_arr) > 0)
            $selectdata[intval($row['type']) - 1] = $select_arr;
        if($row['type'] == '4'){
            foreach ($row['answer'] as $k=>$v){
                $anwer[] = array('value'=>$v);
            }
            $selectdata['3'] = $anwer;
        }
        $this->view->assign("selectdata", $selectdata);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }


    /**
     * 导入
     */
    public function import()
    {
        return parent::import();
    }
}