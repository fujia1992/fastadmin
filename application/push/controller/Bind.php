<?php

namespace app\push\controller;

use think\worker\Server;
use GatewayClient\Gateway;

class Bind extends Server
{

    public function index(){
        $client_id = $_POST['client_id'];
        // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值(ip不能是0.0.0.0)
        Gateway::$registerAddress = '127.0.0.1:1236';
        // 假设用户已经登录，用户uid和群组id在session中
        $uid      = $_SESSION['uid'];
        $group_id = $_SESSION['group'];
        // client_id与uid绑定
        Gateway::bindUid($client_id, $uid);
        // 加入某个群组（可调用多次加入多个群组）
        //Gateway::joinGroup($client_id, $group_id);
    }

    public function AjaxSendMessageAction () {
        $message = $_POST['message'];
        // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
        Gateway::$registerAddress = '127.0.0.1:1236';
        GateWay::sendToAll($message);
        // 向任意uid的网站页面发送数据
        //Gateway::sendToUid($uid, $message);
        // 向任意群组的网站页面发送数据
        //Gateway::sendToGroup($group, $message);
    }
}