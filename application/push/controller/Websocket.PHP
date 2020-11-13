<?php

namespace app\push\controller;

use app\push\controller\BaseServer;

class Websocket extends BaseServer {
    protected $config;

    public function __construct()
    {

        //实例化BusinessWorker进程,业务处理进程================
        $this->config[ 'business' ] = Config ('worker.business') == '' ? '' : Config ('worker.business');

        parent::__construct();
    }
}
