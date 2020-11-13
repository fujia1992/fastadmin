<?php

namespace app\push\controller;

use Workerman\Worker;
use app\push\controller\BaseServer;

class Workerman extends BaseServer {
    protected $config;

    public function __construct()
    {
        // WebServer
        $web = new Worker("http://0.0.0.0:55151");
        // WebServer进程数量
        $web->count = 2;
        parent::__construct();
    }
}
