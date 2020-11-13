<?php

namespace addons\kaoshi\controller;

use app\common\controller\Frontend;
use Think\Db;
use think\addons\Controller;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class UserPlan extends Controller
{

    /**
     * UserPlan模型对象
     * @var \addons\kaoshi\model\examination\KaoshiUserPlan
     */
    protected $model = null;
    protected $layout = 'default';

    public function _initialize()
    {
        parent::_initialize();
        if (!$this->auth->isLogin()) {
            $this->error("请先登录", url('index/user/login'));
        }
        $this->model = new \addons\kaoshi\model\examination\KaoshiUserPlan;
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 查看学习计划
     */
    public function study()
    {
        $map['a.user_id'] = $this->auth->id;
        $map['b.type'] = 1;
        $map['b.deletetime'] = null;
        $field = 'a.plan_id, b.plan_name, b.times, b.hours, b.endtime, b.starttime,b.type,b.exam_id,c.exam_name, max(d.lasttime) as lasttime';
        $plans = $this->model
            ->alias('a')
            ->where($map)
            ->field($field)
            ->join('fa_kaoshi_plan b', 'a.plan_id = b.id')
            ->join('fa_kaoshi_exams c', 'b.exam_id = c.id')
            ->join('fa_kaoshi_user_exams d', 'a.id = d.user_plan_id', 'left')
            ->group('b.id')
            ->select();

        foreach ($plans as $key => $value) {

            if ($value['lasttime'] != 0) {
                $value['lasttime'] = $this->timediff($value['lasttime'], time());

            } else {
                $value['lasttime'] = "未开始";
            }
        }
        $this->view->assign('plans', $plans);
        $this->view->assign('title', "在线学习");
        return $this->view->fetch();
    }

    /**
     * 查看学习记录
     */
    public function studyhistory()
    {
        // $map['a.status'] = 1;
        $map['c.type'] = 1;
        $map['c.deletetime'] = NULL;
        $map['b.user_id'] = $this->auth->id;
        $field = "a.id, a.score, a.questionsdata, a.status, a.lasttime, a.usetime, a.answersdata, c.plan_name, d.exam_name";
        $order = 'a.lasttime desc';
        $history = Db::name('KaoshiUserExams')
            ->alias('a')
            ->join('__KAOSHI_USER_PLAN__ b', 'b.id = a.user_plan_id')
            ->join('__KAOSHI_PLAN__ c', 'c.id = b.plan_id')
            ->join('__KAOSHI_EXAMS__ d', 'd.id = c.exam_id')
            // ->join('__USER__ e','e.id = b.user_id')
            ->where($map)->field($field)->order($order)->select();
        if (is_array($history) && count($history) > 0) {
            foreach ($history as $key => $value) {

                $history[$key]['number'] = 0;
                $history[$key]['finished'] = 0;
                $questions = json_decode($value['questionsdata'], true);
                if (is_array($questions) && count($questions) > 0) {
                    foreach ($questions as $k => $vo) {
                        $history[$key]['number'] += count($vo['timu']);
                    }
                }
                $answers = json_decode($value['answersdata'], true);
                if (is_array($answers) && count($answers) > 0) {

                    foreach ($answers as $step => $item) {
                        $history[$key]['finished'] = count($item);
                    }
                }
                unset($history[$key]['questionsdata']);
                unset($history[$key]['answersdata']);
            }
        }
        $this->view->assign('history', $history);
        $this->view->assign('title', "我的学习");
        return $this->view->fetch();
    }

    /**
     * 查看考试计划
     */
    public function exam()
    {
        $map['a.user_id'] = $this->auth->id;
        $map['b.type'] = 0;
        $map['b.deletetime'] = null;
        $map['b.endtime'] = ['>', time()];
        $map['b.starttime'] = ['<', time()];
        $field = 'a.id, a.plan_id, b.plan_name, b.times, b.hours, b.endtime, b.starttime,b.type,b.exam_id,c.exam_name';
        $plans = $this->model
            ->alias('a')
            ->where($map)
            ->field($field)
            ->join('__KAOSHI_PLAN__ b', 'a.plan_id = b.id')
            ->join('__KAOSHI_EXAMS__ c', 'b.exam_id = c.id')
            ->group('b.id')
            ->select();

        $this->view->assign('plans', $plans);
        $this->view->assign('title', "在线考试");
        return $this->view->fetch();
    }

    /**
     * 查看考试记录
     */
    public function examhistory()
    {
        $map['a.status'] = 1;
        $map['c.type'] = 0;
        $map['c.deletetime'] = NULL;
        $map['b.user_id'] = $this->auth->id;
        $field = "a.id, a.score, c.plan_name, d.exam_name";
        $order = 'a.lasttime desc';
        $history = Db::name('KaoshiUserExams')
            ->alias('a')
            ->join('__KAOSHI_USER_PLAN__ b', 'b.id = a.user_plan_id')
            ->join('__KAOSHI_PLAN__ c', 'c.id = b.plan_id')
            ->join('__KAOSHI_EXAMS__ d', 'd.id = c.exam_id')
            // ->join('__USER__ e','e.id = b.user_id')
            ->where($map)->field($field)->order($order)->select();
        $this->view->assign('history', $history);
        $this->view->assign('title', "考试记录");
        return $this->view->fetch();
    }

    //PHP 计算两个时间戳之间相差的时间
    //功能：计算两个时间戳之间相差的日时分秒
    //$begin_time  开始时间戳
    //$end_time 结束时间戳
    public function timediff($begin_time, $end_time)
    {
        if ($begin_time < $end_time) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        //计算天数
        $timediff = $endtime - $starttime;
        $days = intval($timediff / 86400) ? intval($timediff / 86400) . '天前' : '';
        if ($days) {
            return $days;
        }
        //计算小时数
        $remain = $timediff % 86400;
        $hours = intval($remain / 3600) ? intval($remain / 3600) . '小时前' : '';
        if ($hours) {
            return $hours;
        }
        //计算分钟数
        $remain = $remain % 3600;
        $mins = intval($remain / 60) ? intval($remain / 60) . '分钟前' : '';
        if ($mins) {
            return $mins;
        }
        //计算秒数
        $secs = $remain % 60;

        $res = ($secs ? $secs . '秒' : '') . '前';

        return $res;
    }

}
