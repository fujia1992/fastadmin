<?php

namespace addons\kaoshi\controller;

use app\common\controller\Frontend;
use app\common\library\Sms;
use think\Config;
use think\Cookie;
use think\Hook;
use think\Session;
use think\Validate;
use think\addons\Controller;


/**
 * 会员中心
 */
class User extends Controller
{
    protected $layout = 'default';

    public function _initialize()
    {
        parent::_initialize();
        $auth = $this->auth;
        if (!$this->auth->isLogin()) {
            $this->error("请先登录", url('index/user/login'));
        }
    }

    /**
     * 会员中心
     */
    public function index()
    {

        $this->view->assign('title', "用户中心");
        return $this->view->fetch();
    }

    /**
     * 修改密码
     */
    public function changepwd()
    {
        if ($this->request->isPost()) {
            $oldpassword = $this->request->post("oldpassword");
            $newpassword = $this->request->post("newpassword");
            $renewpassword = $this->request->post("renewpassword");
            $token = $this->request->post('__token__');
            $rule = [
                'oldpassword' => 'require|length:6,30',
                'newpassword' => 'require|length:6,30',
                'renewpassword' => 'require|length:6,30|confirm:newpassword',
                '__token__' => 'token',
            ];

            $msg = [
            ];
            $data = [
                'oldpassword' => $oldpassword,
                'newpassword' => $newpassword,
                'renewpassword' => $renewpassword,
                '__token__' => $token,
            ];
            $field = [
                'oldpassword' => "旧密码",
                'newpassword' => "新密码",
                'renewpassword' => "确认密码"
            ];
            $validate = new Validate($rule, $msg, $field);
            $result = $validate->check($data);
            if (!$result) {
                $this->error(__($validate->getError()), null, ['token' => $this->request->token()]);
                return false;
            }

            $ret = $this->auth->changepwd($newpassword, $oldpassword);
            if ($ret) {
                $this->success("修改密码成功", url('index/user/login'));
            } else {
                $this->error($this->auth->getError(), null, ['token' => $this->request->token()]);
            }
        }
        $this->view->assign('title', "修改密码");
        return $this->view->fetch();
    }
}
