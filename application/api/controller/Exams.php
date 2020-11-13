<?php

namespace app\api\controller;

use app\common\controller\Frontend;
use think\Db;
use app\common\controller\Api;
use think\db\Where;


/**
 * 在线考试接口
 */
class Exams extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\examination\KaoshiExams;
        $this->KaoshiUserExams = new \app\admin\model\kaoshi\examination\KaoshiUserExams;
        $this->KaoshiUserTime = new \app\admin\model\kaoshi\examination\KaoshiUserTime;
        $this->KaoshiQuestions = new \app\admin\model\kaoshi\examination\KaoshiQuestions;
        $this->KaoshiUserAnswer = new \app\admin\model\kaoshi\examination\KaoshiUserAnswer;
        $this->typeList = ["1" => "单选题", "2" => "多选题", "3" => "判断题","4" => "填空题","5" => "简答题"];
    }

    /**
     * 查看所有考卷
     * @ApiMethod (POST)
     * @param int $offset 页数
     * @param int $uid 用户ID
     * @ApiReturn ({"code": "1成功0失败","msg": "请求成功","time": "请求时间戳","data": {"total": "总数","rows": [{"id": "组卷id","countnum": "答题人数","exam_name": "组卷名称","hours": "答题时间（分钟）","total_score": "题目得分","img": "封面图","exams_stauts": "1可以考试2缺考3批阅中4批阅完毕"}]}})
     */
    public function index()
    {
        $page = $this->request->request("page",1);
        $uid = $this->request->request("uid");
        if (!$uid) {
            $this->error('用户不存在');
        }

        $total = $this->model
            ->order("id desc")
            ->count();

        $list = $this->model
            ->order("id desc")
            ->page($page)
            ->limit(8)
            ->select();

        $list = collection($list)->toArray();
        $domain = $this->request->domain();
        $data =  array();
        $kaoshi_user_exams = Db::name('KaoshiUserExams');
        foreach ($list as $key=>$row) {
            $userexams = $kaoshi_user_exams
                ->where('user_id',$uid)
                ->where('exams_id',$row['id'])
                ->find();
            $countnum = $kaoshi_user_exams
                ->where('exams_id',$row['id'])
                ->count();
            $data[$key]['id'] = $row['id'];
            $data[$key]['countnum'] = $countnum;
            $data[$key]['exam_name'] = $row['exam_name'];
            $data[$key]['hours'] = $row['hours'];
            $data[$key]['time'] = date('Y-m-d H:i:s',$row['createtime']);
            $data[$key]['total_score'] = $userexams['score']?$userexams['score']:0;
            $data[$key]['img'] = $domain.$row['img'];
            if($row['endtime'] <= time() && !$userexams){
                $data[$key]['exams_stauts'] = '2';//缺考
            }else if($row['endtime'] > time() && !$userexams){
                $data[$key]['exams_stauts'] = '1';//可以考试
            }else if($userexams['status'] == 0 && $userexams){
                $data[$key]['exams_stauts'] = '3';//批阅中
            }else if($userexams['status'] == 1){
                $data[$key]['exams_stauts'] = '4';//批阅完毕
            }
            $KaoshiUserTime = Db::name('KaoshiUserTime')->where(array('user_id'=>$uid,'exams_id'=>$row['id']))->find();
            $KaoshiUserAnswer = Db::name('KaoshiUserAnswer')->where(array('user_id'=>$uid,'exams_id'=>$row['id']))->find();
            //计算时间
            if($KaoshiUserTime && !$KaoshiUserAnswer && time() > $KaoshiUserTime['starttime'] + $row['hours']*60){
                $data[$key]['exams_stauts'] = '4';//开始考试后没有答题
            }
        }
        $result = array("total" => $total, "rows" => $data);
        $this->success('请求成功',$result);

    }


    /**
     * 考卷详情
     * @ApiMethod (POST)
     * @param int $exams_id 考卷ID
     * @param int $user_id 用户ID
     * @ApiReturn ({"code": "1成功0失败","msg": "请求成功","time": "请求时间戳","data": {"rows": [{"id": "组卷id","exam_name": "组卷名称","second": "答题时间（秒）","questions_id":"上次答题小题的ID","start_status":"1为点击过开始答题","score": "组题总分","img": "封面图","starttime": "开始时间","endtime": "结束时间"}]},"questionsdata": [{"questions_id": "题目ID","score": "题目分数","type_name": "单选题","type": "1单选2多选3判断4填空5简答","answer": "B（答案）","question": "题目","selectdata": [{"key": "A","value": "选项"}]}]})
     */
    public function detail()
    {
        $params['exams_id'] = $this->request->request("exams_id");
        $data = $this->model->where("id",$params['exams_id'])->find();
        if(!$data){
            $this->error('请求数据失败');
        }
        if($data['endtime'] < time()){
            $this->error('答题时间已过');
        }
        $params['user_id'] = $this->request->request("user_id");
        if(!$params['user_id']){
            $this->error('请求数据失败');
        }
        $KaoshiUserExams = $this->KaoshiUserExams->where(array('user_id'=>$params['user_id'],'exams_id'=>$params['exams_id']))->find();
        if($KaoshiUserExams){
            $this->error('已完成答题');
        }
        $data['start_status'] = 0;
        $data['second'] = $data['hours'] * 60;
        //处理用户答题时间
        $KaoshiUserTime = $this->KaoshiUserTime->where(array('user_id'=>$params['user_id'],'exams_id'=>$params['exams_id']))->order("id asc")->find();
        if($KaoshiUserTime){
            $residuetime = time() - $KaoshiUserTime['starttime'];
            if($residuetime >=0){
                $data['second'] = $data['hours'] * 60 - $residuetime;
            }
            $data['start_status'] = 1;
        }
        if($data['second'] <=0 ){
            $this->error('答题时间超时');
        }
        unset($data['hours']);
        //用户答题记录段点
        $data['questions_id'] = 0;
        $KaoshiUserAnswer = $this->KaoshiUserAnswer->where(array('user_id'=>$params['user_id'],'exams_id'=>$params['exams_id']))->order("id DESC")->find();
        if($KaoshiUserAnswer){
            $data['questions_id'] = $KaoshiUserAnswer['questions_id'];
        }
        //答题选项
        $questionsdata = json_decode($data['questionsdata'], true);
        $question_obj = Db::name('KaoshiQuestions');
        if(!empty($questionsdata)){
            foreach ($questionsdata as $key => $value) {
                $arr = $question_obj->where('id',$value['questions_id'])->find();
                $questionsdata[$key]['type_name'] = $this->typeList[$arr['type']];
                $questionsdata[$key]['type'] = $arr['type'];
                $questionsdata[$key]['selectnumber'] = $arr['selectnumber'];
                $questionsdata[$key]['question'] = $arr['question'];
                $selectdata = json_decode($arr['selectdata'], true);
                if($data['chaos_status'] ==1 && ($arr['type']=='1' || $arr['type'] ==2)){
                    shuffle($selectdata);
                }
                foreach ($selectdata as $k=>$v){
                    $selectdata[$k]['user_key'] = NumToLetter($k);
                }
                $questionsdata[$key]['selectdata'] = $selectdata;
            }
        }
        $result = array( "rows" => $data,'questionsdata'=>$questionsdata);
        $this->success('请求成功',$result);

    }

    /**
     * 考卷时间
     * @ApiMethod (POST)
     * @param int $exams_id 考卷ID
     * @param int $uid 用户UID
     * @ApiReturn ({"code": "1成功0失败","msg": "请求成功"})
     */
    public function examstime()
    {
        $params['exams_id'] = $this->request->request("exams_id");
        $data = $this->model->where("id",$params['exams_id'])->find();
        if(!$data){
            $this->error('请求数据失败');
        }
        $params['user_id'] = $this->request->request("uid");
        if(!$params['user_id'] ){
            $this->error('用户不存在');
        }
        $data = $this->KaoshiUserTime->where(array('user_id'=>$params['user_id'],'exams_id'=>$params['exams_id']))->find();
        if($data){
            $this->error('已开始过答题');
        }
        $params['starttime'] = time();
        Db::startTrans();
        try {
            $result = $this->KaoshiUserTime->allowField(true)->save($params);
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
            $this->success('保存成功');
        } else {
            $this->error(__('No rows were inserted'));
        }

    }



    /**
     * 下一题
     * @ApiMethod (POST)
     * @param int $answer 选择的答案
     * @param int $user_id 用户ID
     * @param int $questions_id 小题ID
     * @param int $exams_id 考试ID
     * @ApiReturn ({"code": "1成功0失败","msg": "请求成功"})
     */
    public function useranswer()
    {
        //前段传过来的答案
        $params['answer'] = $this->request->request("answer");
        if(!$params['answer']){
            //$this->error("请提交答案");
        }
        $params['exams_id'] = $this->request->request("exams_id");
        $exams = $this->model->where('id',$params['exams_id'])->find();
        if(!$exams){
            $this->error("考试数据有误");
        }
        $params['user_id'] = $this->request->request("user_id");
        if(!$params['user_id']){
            $this->error("用户数据有误");
        }
        $params['questions_id'] = $this->request->request("questions_id");
        $KaoshiQuestions = $this->KaoshiQuestions->where('id',$params['questions_id'])->find();
        if(!$KaoshiQuestions){
            $this->error("小题数据有误");
        }
        $KaoshiUserAnswer = $this->KaoshiUserAnswer->where(array('user_id'=>$params['user_id'],'exams_id'=>$params['exams_id'],'questions_id'=>$params['questions_id']))->find();
        if($KaoshiUserAnswer){
            $this->error("已提交过答题");
        }
        //计算分数
        $params['score'] = 0;
        $questionsdata = \GuzzleHttp\json_decode($exams['questionsdata'],true);
        foreach ($questionsdata as $key=>$val){
            if($val['questions_id'] == $params['questions_id']){
                $score = $val['score'];
            }
        }
        if($KaoshiQuestions['type'] == 2){
            $params['answer'] = explode(',',$params['answer']);
            asort($params['answer']);
            $params['answer'] = implode(',',$params['answer']);
        }
        if($KaoshiQuestions['type'] == 4){
            if($params['answer'] != $KaoshiQuestions['answer']){
                $useranswer = explode(',',$params['answer']);
                $answer = explode(',',$KaoshiQuestions['answer']);
                $diff_arr = array_diff($answer,$useranswer);
                $one_score = $score/$KaoshiQuestions['selectnumber'];
                $params['score'] = $one_score*($KaoshiQuestions['selectnumber'] - count($diff_arr));
            }else{
                $params['score'] = $score;
            }
        }
        if($params['answer'] == $KaoshiQuestions['answer'] && $KaoshiQuestions['type'] <4){
            $params['score'] = $score;
        }
        $params['set_score'] = $score;
        $params['questions_type'] = $KaoshiQuestions['type'];
        $result = false;
        Db::startTrans();
        try {
            $result = $this->KaoshiUserAnswer->allowField(true)->save($params);
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
            $this->success('保存成功',array('score'=>$params['score']));
        } else {
            $this->error(__('No rows were inserted'));
        }
    }




    /**
     * 提交答卷
     * @ApiMethod (POST)
     * @param int $exams_id 考卷ID
     * @param int $user_id 用户ID
     * @ApiReturn ({"code": "1成功0失败","msg": "请求成功"})
     */
    public function save()
    {
        $result = false;
        $params['exams_id'] = $this->request->request("exams_id");
        if (!$params['exams_id']) {
            $this->error("组题ID有误");
        }
        $params['user_id'] = $this->request->request("user_id");
        if (!$params['user_id']) {
            $this->error("用户有误");
        }
        //计算答题时间
        //处理用户答题时间
        $KaoshiUserTime = $this->KaoshiUserTime->where(array('user_id'=>$params['user_id'],'exams_id'=>$params['exams_id']))->order("id asc")->find();
        if(!$KaoshiUserTime){
            $this->error("答题时间有误，不能提交");
        }
        //是否答过题
        $KaoshiUserExams = $this->KaoshiUserExams->where(array('user_id'=>$params['user_id'],'exams_id'=>$params['exams_id']))->find();
        if($KaoshiUserExams){
            $this->error('已完成答题,不能重复交卷');
        }
        $params['starttime'] = $KaoshiUserTime['starttime'];
        $params['endtime'] = time();
        //计算分钟数
        $params['usetime'] = intval(($params['endtime'] - $params['starttime'])/60);
        $params['lasttime'] = $params['starttime'];
        $params['user_id'] = $this->request->request("user_id");
        if (!$params['user_id']) {
            $this->error("用户ID有误");
        }
        $KaoshiQuestions = $this->KaoshiUserAnswer->where(array('user_id'=>$params['user_id'],'exams_id'=>$params['exams_id']))->select();
        if(!$KaoshiQuestions){
            $this->error("交卷失败，至少完成一道题");
        }
        $answersdata = array();
        $total_score = 0;
        $status = 1;
        foreach ($KaoshiQuestions as $k=>$v){
            $answersdata[] = array(
                'questions_id'  =>  $v['questions_id'],
                'user_answer'   =>  $v['answer'],
                'score'         =>  $v['score'],
                'set_score'     =>  $v['set_score'],
            );
            if($v['questions_type'] == 5 && $v['answer']){
                $status = 0;
            }
            $total_score += $v['score'];
        }
        $params['score'] = $total_score;
        $params['status'] = $status;
        $params['answersdata'] = \GuzzleHttp\json_encode($answersdata);
        Db::startTrans();
        try {
            $result = $this->KaoshiUserExams->allowField(true)->save($params);
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
            $this->success('保存成功',array('id' => $this->KaoshiUserExams->id,'score'=>$total_score));
        } else {
            $this->error(__('No rows were inserted'));
        }
    }

}
