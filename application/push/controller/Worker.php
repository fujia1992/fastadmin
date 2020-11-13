<?php

namespace app\push\controller;

use app\push\controller\BaseServer;

class Worker extends BaseServer {
	protected $config;
	
	public function __construct()
	{
		//Gateway 进程配置，外部客户端连接进程
		$this->config[ 'gateway' ] = Config ('worker.gateway') == '' ? '' : Config ('worker.gateway');
		parent::__construct();
	}
}
