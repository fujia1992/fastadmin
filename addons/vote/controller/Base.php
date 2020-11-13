<?php

namespace addons\vote\controller;

use think\Config;

/**
 * 投票控制器基类
 */
class Base extends \think\addons\Controller
{

    // 初始化
    public function __construct()
    {
        parent::__construct();
        $config = get_addon_config('vote');
        // 定义投票首页的URL
        $config['indexurl'] = addon_url('vote/index/index', [], false);
        Config::set('vote', $config);
    }

    public function _initialize()
    {
        parent::_initialize();
        if (Config::get('vote.wechatautologin') && $this->isWechat() && !$this->auth->id) {
            $info = get_addon_info('third');
            if (!$info || !$info['state']) {
                $this->error("请先在后台安装配置第三方登录插件");
            }
            header("location:/third/connect/wechat?url=" . urlencode($this->request->url()));
            exit;
        }

        $this->view->assign('isWechat', $this->isWechat());
        $this->view->assign('controllerName', $this->controller);
        // 如果请求参数action的值为一个方法名,则直接调用
        $action = $this->request->post("action");
        if ($action && $this->request->isPost()) {
            return $this->$action();
        }
    }

    /**
     * 判断是否微信
     * @return bool
     */
    public function isWechat()
    {
        if (strpos($this->request->server('HTTP_USER_AGENT'), 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 获取真实IP地址
     * @return mixed
     */
    public function getIp()
    {
        return $this->request->ip(0, Config::get('vote.isipadv'));
    }

    /**
     * 判断是否私有IP
     * @param $ip string
     * @return bool
     */
    public function isPrivateIp($ip)
    {
        $pri_addrs = array(
            '10.0.0.0|10.255.255.255',
            '172.16.0.0|172.31.255.255',
            '192.168.0.0|192.168.255.255',
            '169.254.0.0|169.254.255.255',
            '127.0.0.0|127.255.255.255'
        );

        $long_ip = ip2long($ip);
        if ($long_ip != -1) {
            foreach ($pri_addrs as $pri_addr) {
                list($start, $end) = explode('|', $pri_addr);
                if ($long_ip >= ip2long($start) && $long_ip <= ip2long($end)) {
                    return true;
                }
            }
        }

        return false;
    }
}
