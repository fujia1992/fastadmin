<?php

namespace addons\ykquest\controller;

use app\common\controller\Api;
use addons\ykquest\controller\WxBase;
use addons\ykquest\model\Answerer;

class UserApi extends Api {

    private $config;
    protected $noNeedLogin = ['userlogin']; // 无需登录即可访问的方法，同时也无需鉴权了

    public function _initialize() {
        $this->config = get_addon_config("ykquest");
        parent::_initialize();
    }

    public function userlogin() {
        $code = $this->request->param("code");
        $rawData = $this->request->param("rawData", '', 'trim');
        if (!$code) {
            $this->error("参数不正确");
        }
        $userInfo = (array) json_decode($rawData, true);
        $wxBase = new WxBase($this->config['AppID'], $this->config['AppSecret']);
        $openid = $wxBase->GetAuthSessionKey($code);
        if ($openid) {
            $Answerer = new Answerer();
            $result = array();
            $getInfo = $Answerer->where("openid", $openid)->find();
            if ($getInfo) {
                $result = array(
                    "openid" => $openid,
                    "nickname" => $getInfo['nickname'],
                    "avatarimage" => $getInfo['avatarimage']
                );
                $this->success("登录成功", $result);
            } else {

                $city = $userInfo['province'] . '/' . $userInfo['city'];
                $data = array("openid" => $openid, "nickname" => $userInfo['nickName'], "city" => $city, "createtime" => time(), "updatetime" => time(), "avatarimage" => $userInfo['avatarUrl']);
                $insert = $Answerer->insert($data);
                if ($insert) {
                    $result = array(
                        "openid" => $openid,
                        "nickname" => $userInfo['nickName'],
                        "avatarimage" => $userInfo['avatarUrl']
                    );
                    $this->success("登录成功", $result);
                }
            }
            $this->error("登录失败");
        }
        $this->error("登录失败");
    }

}
