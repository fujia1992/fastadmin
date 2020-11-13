<?php

namespace app\api\controller;

use app\common\controller\Frontend;
use think\Db;
use app\common\controller\Api;


/**
 * 查看考试接口
 */
class Userexams extends Api
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
     * 查看答题
     * @ApiMethod (POST)
     * @param int $user_exams_id 用户答题完ID
     * @ApiReturn ({"code": "1成功0失败","msg": "请求成功","time": "请求时间戳","data": {"total": "总数","rows": [{"exam_name": "组卷名称","usetime": "答题时间（分钟）","total_score": "题目得分","score": "用户得分","starttime": "开始时间","endtime": "结束时间","questionsdata": [{"type_name": "题目类型","type": "1类型:1=单选题,2=多选题,4填空题目,5简答题","answer": "B正确答案","user_answer": "用户答案","score": "10题目分数","user_score": "10用户得分","question": "题目","selectdata": [{"key": "A","value": "Z+8＝32"},{"key": "B","value": "Z÷8＝32"},{"key": "C","value": "32×Z＝8"},{"key": "D","value": "32÷Z＝8"}]}]}]}}) */
    public function index(){
        $user_exams_id = $this->request->post('user_exams_id');
        $user_exams = $this->KaoshiUserExams->get($user_exams_id);
        if(!$user_exams){
            $this->error(__('请求数据失败'));
        }
        $exams = $this->model->get($user_exams['exams_id']);
        if (!$exams) {
            $this->error(__('请求数据失败'));
        }
        $user_exams['exam_name'] = $exams['exam_name'];
        $user_exams['total_score'] = $exams['score'];
        $exams['questionsdata'] = \GuzzleHttp\json_decode($exams['questionsdata'],true);
        $userinfo = Db::name('User')->where('id',$user_exams['user_id'])->find();
        $user_exams['username'] = $userinfo['username'];
        $user_exams['nickname'] = $userinfo['nickname'];
        $answersdata = array();
        $answersdata = \GuzzleHttp\json_decode($user_exams['answersdata'],true);
        if(count($answersdata) <= 0){
            $this->error('请求数据失败');
        }
        if(!empty($exams['questionsdata'])){
            foreach ($exams['questionsdata'] as $key => $value) {
                $arr = $this->KaoshiQuestions->where('id',$value['questions_id'])->find();
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
        $result = array( "rows" => $user_exams,'questionsdata' => $questionsdata);
        $this->success('请求成功',$result);
    }
}
