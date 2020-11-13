<?php

namespace addons\ykquest\controller;

use app\common\controller\Api;
use addons\ykquest\model\Answerer;
use addons\ykquest\model\Problem;
use addons\ykquest\model\Reply;
use addons\ykquest\model\Toption;
use addons\ykquest\model\Survey;
use addons\ykquest\model\Myanswer;
use think\Db;

class SurveyApi extends Api {

    protected $noNeedLogin = ['index', 'serList', 'setReplay'];
    protected $singleArr = [0, 1, 2];

    public function index() {
        $ids = $this->request->param("ids");
        $openid = $this->request->param("openid");
        $page = $this->request->param("page");
        $page = (int) $page ? $page : 1;
        if ($page < 1) {
            $page = 1;
        }
        if (!$ids || !$openid) {
            $this->error("参数不正确");
        }
        $resultArr = [];
        $result = array("code" => 1);
        $surveyModel = new Survey();
        $prombleModel = new Problem();
        $answererModel = new Answerer();
        $toptionModel = new Toption();
        $surInfo = $surveyModel->where("id", $ids)->where("status", "1")->Field("id,description,name,starttime,endtime")->find();
        if (!$surInfo) {
            $this->error("您访问问卷不存在");
        }
        $MyModel = new Myanswer();
        $anInfo = $answererModel->where("openid", $openid)->find();
        if (!$anInfo) {
            $this->error("未注册");
        }
        $is = $MyModel->where("survey_id", $ids)->where("answerer_id", $anInfo['id'])->find();
        if ($is) {
            $this->error("您已经参与过该问卷调查，谢谢合作");
        }
        if ($surInfo['starttime'] > time()) {
            $this->error("问卷调查暂未开始");
        }
        if ($surInfo['endtime'] < time()) {
            $this->error("该问卷调查已结束");
        }
        $count = $prombleModel
                ->where("survey_id", $ids)
                ->count();
        $arr = array(
            "id" => "asc",
            "weigh" => "asc"
        );
        $list = $prombleModel
                ->where("survey_id", $ids)
                ->order($arr)
                ->page($page, "10")
                ->select();
        foreach ($list as $val) {
            $val['oplist'] = [];
            if (in_array($val['option_type'], $this->singleArr)) {
                $list = $toptionModel->where("problem_id", $val['id'])->select();
                $val['oplist'] = $list;
            }
            $resultArr[] = $val;
        }
        $result = array("info" => $surInfo, "list" => $resultArr, "count" => $count);
        $this->success("", $result);
    }

    //当前可以参与的问卷
    public function serList() {
        $openid = $this->request->param("openid");
        $page = $this->request->param("page");
        $page = (int) $page ? $page : 1;
        if ($page < 1) {
            $page = 1;
        }
        if (!$openid) {
            $this->error("参数不正确");
        }
        $count = 0;
        $anser = new Answerer();
        $is = $anser->where("openid", $openid)->find();
        $myModel = new Myanswer();
        if (!$is) {
            $result = array("status" => -1);
            $this->error("未注册", $result);
        }
        $where = $myModel->where("answerer_id", $is['id'])->Field("survey_id")->select();
        $serveyModel = new Survey();
        if ($where) {
            $datawhere = [];
            foreach ($where as $val) {
                $datawhere[] = $val['survey_id'];
            }
            $list = $serveyModel->where("status", "1")->where("id", "not in", $datawhere)->where("starttime", "<=", time())->where("endtime", ">=", time())->page($page, 6)->select();
            $count = $serveyModel->where("status", "1")->where("id", "not in", $datawhere)->where("starttime", "<=", time())->where("endtime", ">=", time())->count();
        } else {
            $list = $serveyModel->where("status", "1")->where("starttime", "<=", time())->where("endtime", ">=", time())->page($page, 6)->select();
            $count = $serveyModel->where("status", "1")->where("starttime", "<=", time())->where("endtime", ">=", time())->count();
        }
        if ($list) {
            foreach ($list as $val) {
                $val['starttime'] = date("Y-m-d H:i:s", $val['starttime']);
            }
        }

        $this->success("列表获取成功", array("list" => $list, "count" => $count));
    }

    public function setReplay() {
        $openid = $this->request->param("openid");
        $ser_id = $this->request->param("ser_id");
        $quest = $this->request->param("quest", '', 'trim');
        if (!$openid || !$ser_id || !$quest) {
            $this->error("参数不正确");
        }
        $ser_id = (int) $ser_id;
        $surveyModel = new Survey();
        $toptionModel = new Toption();
        $MyModel = new Myanswer();
        $replyModel = new Reply();
        $proModel = new Problem();
        $ansModel = new Answerer();
        $info = $ansModel->where("openid", $openid)->find();
        if (!$info) {
            $this->error("未注册");
        }
        $surInfo = $surveyModel->where("id", $ser_id)->find();
        if (!$surInfo) {
            $this->error("问卷不存在");
        }
        $is = $MyModel->where("answerer_id", $info['id'])->where("survey_id", $ser_id)->find();
        if ($is) {
            $this->error("该问卷您已经填写,谢谢合作");
        }
        $arr = [];
        $time = time();
//        $tempQustes=json_decode("[".htmlspecialchars_decode($quest)."]",true);
        $tempQustes = $this->object_array(json_decode(htmlspecialchars_decode($quest)));
        foreach ($tempQustes as $key => $val) {
            $key = str_replace("quest[", "", $key);
            $key = str_replace("]", "", $key);
            if (is_array($val)) {
                $val = json_encode($val);
            }
            $arr[] = array(
                "survey_id" => $ser_id,
                "problem_id" => (int) $key,
                "answerer_id" => $info['id'],
                'content' => $val,
                'admin_id' => $surInfo['admin_id'],
                "createtime" => $time,
                "updatetime" => $time
            );
        }
//        echo '<pre>';
//       var_dump($arr);exit;
        if ($arr) {
            $replyModel->startTrans();
            $MyModel->startTrans();
            $insert = $replyModel->insertAll($arr);
            if ($insert) {
                $data = array(
                    "survey_id" => $ser_id,
                    "answerer_id" => $info['id'],
                    "createtime" => time(),
                    "updatetime" => time()
                );
                $myinsert = $MyModel->insert($data);
                if ($myinsert) {
                    $replyModel->commit();
                    $MyModel->commit();
                    $this->success("提交成功");
                }
            }
            $replyModel->rollback();
            $MyModel->rollback();
            $this->error("提交失败,谢谢合作");
        }
        $this->error("提交失败,谢谢合作");
    }

    public function object_array($array) {
        if (is_object($array)) {
            $array = (array) $array;
        } if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }

}
