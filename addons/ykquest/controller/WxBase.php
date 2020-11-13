<?php

namespace addons\ykquest\controller;

/**
 * Description of wxBase
 *
 * @author Administrator
 */
class WxBase {

    private $appid;
    private $appsecret;

    public function __construct($app_id, $app_secret) {
        $this->appid = $app_id;
        $this->appsecret = $app_secret;
    }

    /**
     * [GetAuthSessionKey 根据授权code获取 session_key 和 openid]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-12-30T18:20:53+0800
     * @param    [string]     $authcode       [用户授权码]
     * @return   [string|boolean]             [失败false, 成功返回appid|]
     */
    public function GetAuthSessionKey($authcode) {
        // 请求获取session_key
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $this->appid . '&secret=' . $this->appsecret . '&js_code=' . $authcode . '&grant_type=authorization_code';
        $result = $this->HttpRequestGet($url);
        if (!empty($result['openid'])) {
            return $result['openid'];
        }
        return false;
    }

    /**
     * [HttpRequestGet get请求]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-01-03T19:21:38+0800
     * @param    [string]           $url [url地址]
     * @return   [array]                 [返回数据]
     */
    private function HttpRequestGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);
        return json_decode($res, true);
    }

    /**
     * [HttpRequestPost curl模拟post]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]   $url        [请求地址]
     * @param    [array]    $data       [发送的post数据]
     * @param    [array]    $is_parsing [是否需要解析数据]
     * @return   [array]                [返回的数据]
     */
    private function HttpRequestPost($url, $data, $is_parsing = true) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_POST, true);

        $res = curl_exec($curl);
        if ($is_parsing === true) {
            return json_decode($reponse, true);
        }
        return $res;
    }

}
