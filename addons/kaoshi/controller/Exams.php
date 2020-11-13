<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use think\Db;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Exams extends Frontend
{

    /**
     * Exams模型对象
     * @var \app\common\model\examination\KaoshiExams
     */
    protected $model = null;
    protected $layout = 'default';

    public function _initialize()
    {
        parent::_initialize();
        if (!$this->auth->isLogin()) {
            $this->error("请先登录", url('index/user/login'));
        }
        $this->model = new \addons\kaoshi\model\examination\KaoshiExams;
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

        $this->view->assign('title', "试卷列表");
        return $this->view->fetch('user');
    }

    public function getquestion($user_plan_id = null)
    {
exit;
        if ($this->request->get('user_plan_id')) {
            $user_plan_id = $this->request->get('user_plan_id');
        }
        if($user_plan_id == NULL){
            $this->error("未选择答题试卷！");

        }

        $plan_map = [
            'b.deletetime' => NULL,
            'b.starttime' => ['<', time()],
            'b.endtime' => ['>', time()],
            'a.id' => $user_plan_id,
        ];
        //获取试卷ID
        $user_plan_row = Db::name('KaoshiUserPlan')->alias('a')
            ->join('__KAOSHI_PLAN__ b', 'a.plan_id = b.id')
            ->where($plan_map)
            ->field('a.id,a.user_id,a.plan_id,b.exam_id,b.type,b.endtime,b.starttime,b.type,b.hours,b.times')
            ->find();

        $user_exam_map = ['user_plan_id' => $user_plan_id, 'status' => 1];
        $user_exams_obj = new  \addons\kaoshi\model\examination\KaoshiUserExams;
        $user_exam_count = $user_exams_obj->where($user_exam_map)->count();
        $url = $user_plan_row['type'] == 1 ? addon_url('kaoshi/user_plan/study') : addon_url('kaoshi/user_plan/exam');

        if ($user_plan_row['type'] == 0 && $user_plan_row['times'] != 0 && $user_exam_count >= $user_plan_row['times']) {
            $this->error("考试次数已用尽！", $url);
        }

        $user_exam_map['status'] = 0;
        $user_exam_row = $user_exams_obj->where($user_exam_map)->find();
        $answers = [];

        //获取考卷题目设置
        $row = $this->model->get($user_plan_row['exam_id']);

        $msg = [
            'endtime' => $user_plan_row['endtime'],
            'starttime' => intval($user_exam_row['starttime']),
            'hours' => $user_plan_row['hours'],
            'plan_id' => $user_plan_row['id'],
            'type' => $user_plan_row['type'],
        ];

        if (!$user_exam_row) {

            $settingdata = json_decode($row['settingdata'], true);
            $this->view->assign("row", $row);
            $questions = $settingdata;
            $question_obj = Db::name('KaoshiQuestions');
            //遍历设置题型，获取题目
            $questions_ids = [];
            $real_answers = [];
            if (!$settingdata) {

                $this->error("考卷丢失！", $url);
            }
            foreach ($settingdata as $key => $value) {

                $map['type'] = $value['type'];
                $map['level'] = $value['level'];
                $map['status'] = 1;
                $map['subject_id'] = $row['subject_id'];
                $map['deletetime'] = null;
                //所需题目字段
                $question_field = 'id,question,selectdata,type, annex';

                //获取类型和等级相符的题目
                $arr = $question_obj->field($question_field)->where($map)->select();
                //所需题数
                $questions_num = intval($value['number']);
                //随机出的键值数组
                $rand_arr = [];
                //正确答案数组
                //避免考卷生成后，删除了题目，造成所需题数大于题库数量
                //将重复选取题目
                while ($questions_num > 0) {
                    if (count($arr) < $value['number']) {
                        //当题库数量不足时，选取的题目会重复
                        //array_rand 第二个参数大于1时返回数组
                        $samll_arr = array_rand($arr, count($arr));
                        if (is_array($samll_arr)) {
                            $rand_arr = array_merge($rand_arr, $samll_arr);
                        } else {
                            array_push($rand_arr, $samll_arr);
                        }
                        $questions_num = $questions_num - count($arr);
                    } else {
                        $rand_arr = array_rand($arr, $questions_num);
                        break;
                    }
                }

                if (is_array($rand_arr) && count($rand_arr) > 0) {
                    foreach ($rand_arr as $k => $v) {
                        array_push($questions_ids, $arr[$v]['id']);
                        $arr[$v]['selectdata'] = is_array($arr[$v]['selectdata']) ? $arr[$v]['selectdata'] : json_decode($arr[$v]['selectdata'], true);
                        $questions[$key]['timu'][intval($k)] = $arr[$v];

                    }

                    shuffle($questions[$key]['timu']);
                } else {
                    array_push($questions_ids, $arr[$rand_arr]['id']);
                    $questions[$key]['timu'][0] = $arr[$rand_arr];
                    $questions[$key]['timu'][0]['selectdata'] = json_decode($arr[$rand_arr]['selectdata'], true);

                }

            }
            $questions_ids = implode(',', array_unique($questions_ids));
            $real_answers = $question_obj->where(['id' => ['in', $questions_ids]])->column('id, answer, describe');
            $add = [
                'user_plan_id' => $user_plan_id,
                'questionsdata' => json_encode($questions),
                'starttime' => time(),
                'lasttime' => time(),
                'real_answersdata' => json_encode($real_answers),
            ];

            $result = $user_exams_obj->save($add);
            $msg['user_exams_id'] = $user_exams_obj->id;
            $this->view->assign('real_answers', $real_answers);

        } else {
            $questions = json_decode($user_exam_row['questionsdata'], true);
            $answers = json_decode($user_exam_row['answersdata'], true);

            if (is_array($answers) && count($answers) > 0) {
                foreach ($questions as $key => $value) {

                    if ($value['type'] == 2) {
                        foreach ($value['timu'] as $k => $vo) {
                            if (isset($answers[(intval($key) + 1)][($k + 1) . '_' . $vo['id']])) {
                                $vo_answer = $answers[(intval($key) + 1)][($k + 1) . '_' . $vo['id']];
                                $answers[(intval($key) + 1)][($k + 1) . '_' . $vo['id']] = explode(',', $vo_answer);
                            }

                        }
                    }
                }
            }

            $msg['user_exams_id'] = $user_exam_row['id'];
            $this->view->assign('real_answers', json_decode($user_exam_row['real_answersdata'], true));

        }

        $back = ($user_plan_row['type'] == 0) ? 'exam' : 'study';
        $this->view->assign("typeList", ["1" => "单选题", "2" => "多选题", "3" => "判断题"]);
        $this->view->assign('back', $back);
        $this->view->assign("questions", $questions);
        $this->view->assign("answers", $answers);
        $this->view->assign("msg", $msg);
        $this->view->assign("title", $row['exam_name']);
        return $this->view->fetch();

    }

    public function saveuseranswer($id = NULL)
    {
        if ($this->request->get('id')) {
            $id = $this->request->get('id');
        }
        if ($this->request->isAjax()) {
            if (!isset($this->request->post()['question'])) {
                return 0;
            }
            $answers = $this->request->post()['question'];
            foreach ($answers as $key => $value) {
                foreach ($value as $k => $item) {
                    if (is_array($item)) {
                        $answers[$key][$k] = implode(',', $item);
                    }
                }
            }

            $result = Db::name('KaoshiUserExams')->where('id = ' . $id)->update(['answersdata' => json_encode($answers)]);
            return $answers;
        }
        return 0;

    }

    public function score($id = NULL)
    {
        if ($this->request->get('id')) {
            $id = $this->request->get('id');
        }
        $user_exams_obj = new \addons\kaoshi\model\examination\KaoshiUserExams;

        $user_exams_row = $user_exams_obj
            ->alias('a')
            ->join('__KAOSHI_USER_PLAN__ b', 'b.id = a.user_plan_id', 'left')
            ->join('__KAOSHI_PLAN__ c', 'c.id = b.plan_id', 'left')
            ->field('a.id, b.plan_id,a.score, a.answersdata,a.real_answersdata,a.questionsdata, c.type')
            ->where('a.id', $id)
            ->find();

        $this->view->assign('type', $user_exams_row['type']);
        $real_answers = json_decode($user_exams_row['real_answersdata'], true);
        $questions = json_decode($user_exams_row['questionsdata'], true);
        $score = $user_exams_row['score'];
        $map = [
            'b.plan_id' => $user_exams_row['plan_id'],
        ];
        $scorelist = [];
        $field = 'a.id,b.user_id,count(a.id) as times';
        //获取参与人数
        $number = $user_exams_obj
            ->alias('a')
            ->join('__KAOSHI_USER_PLAN__ b', 'b.id = a.user_plan_id', 'left')
            ->join('__KAOSHI_PLAN__ c', 'b.plan_id = c.id', 'left')
            ->field($field)
            ->where($map)
            ->group('b.user_id')
            ->count();
        if ($this->request->isPost()) {
            if (isset($this->request->post()['question'])) {
                $answers = $this->request->post()['question'];
            } else {
                $answers = json_decode($user_exams_row['answersdata'], true);
            }
            if (is_array($answers) && count($answers) > 0) {
                foreach ($answers as $key => $value) {

                    foreach ($value as $k => $vo) {
                        $timu_id = explode('_', $k)[1];

                        if (is_array($vo)) {
                            $answers[$key][$k] = implode(',', $vo);
                        }
                        if ($real_answers[$timu_id]['answer'] == $answers[$key][$k]) {
                            $scorelist[$key][$k] = $questions[intval($key) - 1]['mark'];
                            $score += $questions[intval($key) - 1]['mark'];
                        } else {
                            $scorelist[$key][$k] = 0;
                        }

                    }

                }
            }
            $update = [
                'answersdata' => json_encode($answers),
                'scorelistdata' => json_encode($scorelist),
                'score' => $score,
                'status' => 1,
            ];
            $result = Db::name('KaoshiUserExams')->where('id = ' . $id)->update($update);

        }
        $this->view->assign('score', $score);
        $this->view->assign('number', $number);
        $this->view->assign('msg', $user_exams_row);

        return $this->view->fetch();

    }


    public function lefttime($id = NULL)
    {
        if ($this->request->get('id')) {
            $id = $this->request->get('id');
        }
        $lefttime = time() - Db::name('KaoshiUserExams')->where(['id' => $id])->find()['starttime'];
        if ($lefttime < 0) {
            $lefttime = 0;
        }

        exit("{$lefttime}");
    }

    public function answercard($id = NULL)
    {
        if ($this->request->get('id')) {
            $id = $this->request->get('id');
        }
        $user_exams = Db::name('KaoshiUserExams')->where(['id' => $id])->find();
        $questions = json_decode($user_exams['questionsdata'], true);
        $answers = json_decode($user_exams['answersdata'], true);
        $real_answers = json_decode($user_exams['real_answersdata'], true);
        if (count($answers) > 0) {
            foreach ($questions as $key => $value) {

                if ($value['type'] == 2) {
                    foreach ($value['timu'] as $k => $vo) {
                        if (isset($answers[(intval($key) + 1)][($k + 1) . '_' . $vo['id']])) {
                            $vo_answer = $answers[(intval($key) + 1)][($k + 1) . '_' . $vo['id']];
                            $answers[(intval($key) + 1)][($k + 1) . '_' . $vo['id']] = explode(',', $vo_answer);
                        }

                    }
                }
            }
        }
        $this->view->assign("typeList", ["1" => "单选题", "2" => "多选题", "3" => "判断题"]);
        $this->view->assign('user_plan_id', $user_exams['user_plan_id']);
        $this->view->assign('questions', $questions);
        $this->view->assign('answers', $answers);
        $this->view->assign('real_answers', $real_answers);
        $this->view->assign('title', '考试答案');

        return $this->view->fetch();

    }

    public function planrank($type = 0)
    {
        if ($this->request->get('type')) {
            $type = $this->request->get('type');
        }
        $title = "考试排行榜";
        if ($type == 1) {
            $title = "学习排行榜";

        }
        $map = [
            'b.user_id' => $this->auth->id,
            'c.type' => $type,
        ];
        $plans = Db::name('KaoshiUserPlan')->alias('b')
            ->join('__KAOSHI_PLAN__ c', 'c.id = b.plan_id')
            ->where($map)
            ->column('b.plan_id');
        $rank = [];
        foreach ($plans as $key => $value) {
            $plan_score = Db::name('KaoshiUserExams')->alias('a')
                ->join('__KAOSHI_USER_PLAN__ b', 'b.id = a.user_plan_id')
                ->join('__KAOSHI_PLAN__ c', 'c.id = b.plan_id')
                ->order('a.score desc')
                ->group('a.user_plan_id')
                ->where(['c.id' => $value, 'a.status' => 1])
                ->column('a.score');

            $user_score = Db::name('KaoshiUserExams')->alias('a')
                ->join('__KAOSHI_USER_PLAN__ b', 'b.id = a.user_plan_id')
                ->join('__KAOSHI_PLAN__ c', 'c.id = b.plan_id')
                ->join('__USER__ d', 'd.id = b.user_id')
                ->order('a.score desc')
                ->field('a.id, d.nickname, a.score, c.plan_name, min(a.starttime)')
                ->where(['c.id' => $value, 'a.status' => 1, 'b.user_id' => $this->auth->id])
                ->find();
            if (!$user_score) {
                continue;
            }
            if (is_array($plan_score)) {
                $user_score['ranking'] = intval(array_search($user_score['score'], $plan_score)) + 1;

            } else {
                $user_score['ranking'] = 1;

            }
            $rank[$value] = $user_score;

        }

        $this->view->assign('rank', $rank);
        $this->view->assign('title', $title);
        return $this->view->fetch();

    }

    public function rank($type = 0)
    {
        if ($this->request->get('type')) {
            $type = $this->request->get('type');
        }
        $title = "考试排行榜";
        if ($type == 1) {
            $title = "学习排行榜";

        }

        $maxscore = Db::name('KaoshiUserExams')->alias('a')
            ->join('__KAOSHI_USER_PLAN__ b', 'b.id = a.user_plan_id')
            ->join('__KAOSHI_PLAN__ c', 'c.id = b.plan_id')
            ->join('__USER__ d', 'd.id = b.user_id')
            ->field('max(a.score) as maxscore, c.plan_name, d.nickname,a.user_plan_id, b.plan_id,b.user_id')
            ->where(['c.type' => $type, 'a.status' => 1, 'c.deletetime' => NULL])
            ->group('a.user_plan_id')
            ->order('maxscore desc')
            ->buildSql();

        $rank = Db::name('User')->alias('u')->join([$maxscore => 'm'], 'u.id = m.user_id')->field('sum(m.maxscore) as score,m.*')->group('m.user_id')->order('score desc')->select();
        $my = [];
        foreach ($rank as $key => $value) {
            $value['ranking'] = intval($key) + 1;
            if ($value['user_id'] == $this->auth->id) {
                $my = $value;
            }
            $rank[$key] = $value;
        }

        $lasttime = Db::name('KaoshiUserExams')->where(['lasttime' => ['>', '0']])->max('lasttime');
        $starttime = Db::name('KaoshiUserExams')->where(['starttime' => ['>', '0']])->min('starttime');
        $this->view->assign('lasttime', $lasttime);
        $this->view->assign('starttime', $starttime);
        $this->view->assign('my', $my);
        $this->view->assign('rank', $rank);
        $this->view->assign('title', $title);
        return $this->view->fetch();
    }

}
