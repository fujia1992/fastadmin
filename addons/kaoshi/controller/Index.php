<?php

namespace addons\kaoshi\controller;

use think\addons\Controller;
use app\common\controller\Frontend;

class Index extends Controller
{

    protected $layout = 'default';


    public function index()
    {
        if (!$this->auth->isLogin()) {
            $this->error("请先登录", url('index/user/login'));
        }
        $this->view->assign('username', $this->auth->username);

        return $this->view->fetch();
    }


}
