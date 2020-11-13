<?php

namespace app\push\controller;

use app\push\controller\BaseServer;

class Webserver extends BaseServer {
    protected $config;

    public function __construct()
    {
        //实例化Register服务==============内部注册进程=====================
        $this->config[ 'register' ] = Config ('worker.register') == '' ? '' : Config ('worker.register');

        parent::__construct();
    }
}
