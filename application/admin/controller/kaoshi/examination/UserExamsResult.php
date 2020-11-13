<?php

namespace app\admin\controller\kaoshi\examination;

use app\common\controller\Backend;
use think\Db;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class UserExamsResult extends Backend {

    /**
     * UserExams模型对象
     * @var \app\admin\model\kaoshi\examination\KaoshiUserExams
     */
    protected $model = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\examination\KaoshiUserExams;
        $this->typeList = ["1" => "单选题", "2" => "多选题", "3" => "判断题","4" => "填空题","5" => "简答题"];
        $this->view->assign("typeList", $this->typeList);
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
                ->with(['exams', 'user'])
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['exams', 'user'])
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            foreach ($list as $key=>$val) {
                //$answersdata = \GuzzleHttp\json_decode($val['answersdata'],true);
                //$list[$key]['answersdata'] = $answersdata;
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
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
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                foreach ($params['user_score'] as $key => $value) {
                    if ($value == '') {
                        $this->error("请填写简答题" . ($key + 1) . "的分数");
                    }
                    if($value > $params['total_score'][$key]){
                        $this->error("请勿设置简答题" . ($key + 1) . "大于" . $params['total_score'][$key] . "的分数");
                    }
                    $params['score'] += $value;
                }
                $answersdata = array();
                $answersdata = \GuzzleHttp\json_decode($row['answersdata'],true);
                foreach ($answersdata as $k=>$v){
                    foreach ($params['questions_id'] as $ke=>$va){
                        if($va == $v['questions_id']){
                            $answersdata[$k]['score'] = $params['user_score'][$ke];
                        }
                    }
                }
                $params['answersdata'] = json_encode($answersdata);
                $params['status'] = 1;
                //echo"<pre>";print_r($params);exit;
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
        }
        $exams = Db::name('KaoshiExams')->where('id',$row['exams_id'])->find();
        $row['exam_name'] = $exams['exam_name'];
        $row['total_score'] = $exams['score'];
        $exams['questionsdata'] = \GuzzleHttp\json_decode($exams['questionsdata'],true);
        $userinfo = Db::name('User')->where('id',$row['user_id'])->find();
        $row['username'] = $userinfo['username'];
        $row['nickname'] = $userinfo['nickname'];
        $answersdata = array();
        if($row['answersdata']){
            $answersdata = \GuzzleHttp\json_decode($row['answersdata'],true);
            if(count($answersdata) <= 0){
                $this->error('请求数据失败');
            }
        }
        $question_obj = Db::name('KaoshiQuestions');
        if(!empty($exams['questionsdata'])){
            foreach ($exams['questionsdata'] as $key => $value) {
                $arr = $question_obj->where('id',$value['questions_id'])->find();
                $questionsdata[$key]['type_name'] = $this->typeList[$arr['type']];
                $questionsdata[$key]['questions_id'] = $value['questions_id'];
                $questionsdata[$key]['type'] = $arr['type'];
                $questionsdata[$key]['answer'] = $arr['answer'];
                $questionsdata[$key]['user_answer'] = '未作答';
                $questionsdata[$key]['score'] = $value['score'];
                $questionsdata[$key]['user_score'] = '0';
                foreach ($answersdata as $k=>$v){
                    if($value['questions_id'] == $v['questions_id']) {
                        $questionsdata[$key]['user_answer'] = $v['user_answer']?$v['user_answer']:'未作答';
                        $questionsdata[$key]['user_score'] = $v['score'];
                    }
                }
                $questionsdata[$key]['question'] = $arr['question'];
                $questionsdata[$key]['selectdata'] = json_decode($arr['selectdata'], true);
                if ($arr['type'] == 2 || $arr['type']=='4') {
                    $questionsdata[$key]['answer'] = explode(',', $arr['answer']);
                }
            }
        }
        $this->view->assign("row", $row);
        $this->view->assign("questions", $questionsdata);
        return $this->view->fetch();
    }
}
