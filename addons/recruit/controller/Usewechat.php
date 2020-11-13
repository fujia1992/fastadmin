<?php

namespace addons\recruit\controller;

use EasyWeChat\Foundation\Application;
use addons\third\model\Third;

/**
 * 首页
 */
class Usewechat extends Base
{
	protected $noNeedLogin = '*';

    protected $miniProgram = null;
	public function _initialize()
    {
        parent::_initialize();

        $config = get_addon_config('recruit');
        $options = [
                    'debug'  => true,

                    'mini_program' => [
                        'app_id'   => $config['wxappid'],
                        'secret'   => $config['wxappsecret'],
                        'token'    => 'component-token',
                        'aes_key'  => 'component-aes-key'
                        ],
                    ];
        $app = new Application($options);
        $this->miniProgram = $app->mini_program;
        //$this->appid = $config['wxappid'];
        //$this->appsecret = $config['wxappsecret'];
    }

    public function index()
    {
        //$TokenClass = new  AccessToken($this->appid,$this->appsecret);
        //echo $this->appid;
        //echo $this->appsecret;
        //echo $TokenClass->getToken();

		//$app = new Application(get_addon_config('wechat'));
		//$this->assign('access_token',$app->access_token->getToken());

    	//$TokenClass = new  AccessToken($this->appid,$this->appsecret);
    	//$img = $TokenClass->getQrCode_resume(14);

        //print_r($img);
        //$TokenClass = new AccessToken($this->appid,$this->appsecret);
        //$LiteQrcode = new LiteQrcode($TokenClass);
        //echo $LiteQrcode->get_resume(14);

        //echo $this->miniProgram->qrcode->appCodeUnlimit(14,'page/zh_resume/ShowOneResume',418,true);

        $img = $this->miniProgram->qrcode->appCodeUnlimit(14,'page/zh_index/index',418,true,null,true);
        return response($img, 418)->contentType("image/jpg");
    }

    public function get_resume_QrPng(){
        $id = $this->request->param()['id'];

        $img = $this->miniProgram->qrcode->appCodeUnlimit($id,'page/zh_resume/ShowOneResume',418,true,null,true);
        return response($img, 418)->contentType("image/jpg");
    }

    public function get_Job_QrPng(){
        $id = $this->request->param()['id'];

        $img = $this->miniProgram->qrcode->appCodeUnlimit($id,'page/zh_Jobs/ShowOneJob',418,true,null,true);
        return response($img, 418)->contentType("image/jpg");
    }

    public function get_News_QrPng(){
        $id = $this->request->param()['id'];

        $img = $this->miniProgram->qrcode->appCodeUnlimit($id,'page/zh_news/index',418,true,null,true);
        return response($img, 418)->contentType("image/jpg");
    }

    public function get_PhoneNum(){
        $encryptedData = $this->request->post("encryptedData");
        $iv = $this->request->post("iv");

        $third = Third::where(['user_id' => $this->auth->id, 'platform' => 'wxapp'])->find();
        $sessionKey = $third['access_token'];

        $this->success('', $this->miniProgram->encryptor->decryptData($sessionKey, $iv, $encryptedData));
    }



	
}