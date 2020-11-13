<?php

namespace addons\ykquest\controller;

use think\addons\Controller;
use addons\ykquest\model\Survey;
use addons\ykquest\model\Answerer;
use addons\ykquest\model\Myanswer;
use addons\ykquest\model\Problem;
use addons\ykquest\model\Toption;
use addons\ykquest\model\Reply;

class Index extends Controller {

    protected $noNeedLogin = ["index"];
    protected $singleArr = [0, 1, 2];

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $limit = 12;
        $surveyModel = new Survey();
        $list = $surveyModel->where("starttime", "<=", time())->where("status", "1")->where("endtime", ">=", time())->paginate($limit);
        $page = $list->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        return $this->view->fetch();
    }

    public function detail() {
        $id = $this->request->param("id");
        $surveyModel = new Survey();
        $find = $surveyModel->where("id", $id)->find();
        if (!$find) {
            return $this->error("问卷不存在", addon_url("ykquest/index/index"));
        }
        if ($find['starttime'] > time()) {
            return $this->error("该问卷暂未开放", addon_url("ykquest/index/index"));
        }
        if ($find['endtime'] < time()) {
            return $this->error("该问卷暂已结束", addon_url("ykquest/index/index"));
        }
        if ($find['status'] != 1) {
            return $this->error("该问卷暂不支持答题", addon_url("ykquest/index/index"));
        }
        $isAnsr = true;
        $anseModel = new Answerer();
        $myanseModel = new Myanswer();
        $proMolde = new Problem();
        $toptionModel = new Toption();
        $aninfo = $anseModel->where("user_id", $this->auth->id)->find();
        if ($aninfo) {
            $is = $myanseModel->where("answerer_id", $aninfo['id'])->where("survey_id", $id)->find();
            if ($is) {
                $isAnsr = false;
            }
        }
        $arr = array(
            "id" => "asc",
            "weigh" => "asc"
        );
        $list = $proMolde->where("survey_id", $id)
                ->order($arr)
                ->select();
        $proList = [];
        foreach ($list as $val) {
            $val['oplist'] = [];
            if (in_array($val['option_type'], $this->singleArr)) {
                $list = $toptionModel->where("problem_id", $val['id'])->select();
                $val['oplist'] = $list;
            }
            $proList[] = $val;
        }

        $this->assign("proList", $proList);
        $this->assign("isAnser", $isAnsr);
        $this->assign("suery", $find);
        return $this->view->fetch();
    }

    public function Answer() {
        $serId = $this->request->param("survey_id");
        $row = $this->request->param("row/a");
        $surveyModel = new Survey();
        $anseModel = new Answerer();
        $myanseModel = new Myanswer();
        $proMolde = new Problem();
        $toptionModel = new Toption();
        $replyModel = new Reply();
        $servery = $surveyModel->where("id", $serId)->find();
        if (!$servery) {
            return $this->error("问卷不存在");
        }
        if ($servery['status'] != 1) {
            return $this->error('该问卷未开放', addon_url("ykquest/index/index"));
        }
        if ($servery['starttime'] > time()) {
            return $this->error("该问卷暂未开放", addon_url("ykquest/index/index"));
        }
        if ($servery['endtime'] < time()) {
            return $this->error("该问卷暂已结束", addon_url("ykquest/index/index"));
        }
        $anInfo = $anseModel->where("user_id", $this->auth->id)->find();
        if ($anInfo) {
            $user_id = $anInfo['id'];
        } else {
            $data = array(
                'user_id' => $this->auth->id,
                "openid" => uniqid(),
                "status" => 0,
                "avatarimage" => $this->auth->avatar,
                "city" => '',
                'nickname' => $this->auth->nickname,
                "createtime" => time(),
                "updatetime" => time(),
            );
            $user_id = $anseModel->insertGetId($data);
            if (!$user_id) {
                return $this->error("操作失败", addon_url("ykquest/index/index"));
            }
        }
        $isAnser = $myanseModel->where('answerer_id', $user_id)->where("survey_id", $serId)->find();
        if ($isAnser) {
            return $this->error("已经参与过该问卷", addon_url("ykquest/index/index"));
        }
        $arr = array();
        foreach ($row as $key => $val) {
            if (is_array($val)) {
                $val = '[' . implode(',', $val) . ']';
            }
            $arr[] = array(
                'survey_id' => $serId,
                "problem_id" => $key,
                "answerer_id" => $user_id,
                "content" => $val,
                "admin_id" => $servery['admin_id'],
                "createtime" => time(),
                "updatetime" => time(),
            );
        }
        $replyModel->startTrans();
        $myanseModel->startTrans();
        $insert = $replyModel->insertAll($arr);
        if ($insert) {
            $myData = array("survey_id" => $serId, "answerer_id" => $user_id, "createtime" => time(), "updatetime" => time());
            $myInser = $myanseModel->insert($myData);
            if ($myInser) {
                $replyModel->commit();
                $myanseModel->commit();
                return $this->success("提交成功，谢谢合作", addon_url("ykquest/index/index"));
            }
        }
        $replyModel->rollback();
        $myanseModel->rollback();
        return $this->error("答卷失败,谢谢合作", addon_url("ykquest/index/index"));
    }

}
