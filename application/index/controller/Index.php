<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use Firebase\JWT\JWT;
use \think\Loader;
use Workerman\Worker;
use think\Db;
use think\Cookie;


class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\KaoshiSubject;
    }

    public function index()
    {
        $a = $this->fn(15,7);
        //$this->worker();
        //过滤敏感词
        $target = '';
        Loader::import('dirty.dirty');
        $dirty = new \qp_dirty();
        $arr = $dirty->replace_dirty("洪志你为什么这么厉害？", $target);
        //echo $target;
        //print_r($arr);
        return $this->view->fetch();
    }

    public function jwt(){
        $key = "fujia";  //这里是自定义的一个随机字串，应该写在config文件中的，解密时也会用，相当    于加密中常用的 盐  salt
        $token = [
            //"iss"=>"",  //签发者 可以为空
            //"aud"=>"", //面象的用户，可以为空
            //"iat" => time(), //签发时间
            //"nbf" => time(), //在什么时候jwt开始生效  （这里表示生成100秒后才生效）
            "exp" => time()+7200, //token 过期时间
            "uid" => 1,//记录的userid的信息，这里是自已添加上去的，如果有其它信息，可以再添加数组的键值对
            "username"=> "付加"
        ];
        $jwt = JWT::encode($token,$key,"HS256"); //根据参数生成了 token
        print_r($jwt);exit;
    }

    public function check(){
        $jwt = input("token");  //上一步中返回给用户的token
        $key = "fujia";  //上一个方法中的 $key 本应该配置在 config文件中的
        $info = JWT::decode($jwt,$key,["HS256"]); //解密jwt
        print_r($info);
    }

    function img(){
        $url = $this->request->param('src');
        // 可以先进行身份判断有无访问权限
        $img = ROOT_PATH.'/public'.$url;
        $info = getimagesize($img);
        $imgExt = image_type_to_extension($info[2], false);  //获取文件后缀
        $fun = "imagecreatefrom{$imgExt}";
        $imgInfo = $fun($img); 					//1.由文件或 URL 创建一个新图象。如:imagecreatefrompng ( string $filename )
        //$mime = $info['mime'];
        $mime = image_type_to_mime_type(exif_imagetype($img)); //获取图片的 MIME 类型
        header('Content-Type:'.$mime);
        $quality = 100;
        if($imgExt == 'png') $quality = 9;		//输出质量,JPEG格式(0-100),PNG格式(0-9)
        $getImgInfo = "image{$imgExt}";
        $getImgInfo($imgInfo, null, $quality);	//2.将图像输出到浏览器或文件。如: imagepng ( resource $image )
        imagedestroy($imgInfo);
    }

    //下载附件方法
    public function download()
    {
        $local_file = ROOT_PATH.'/public/uploads/20200929/77e0a96258092f1c80025a6e36fc4c2b.jpg';
        if(file_exists($local_file) && is_file($local_file)){
            //以附件形式输出
            header('Content-Description: File Transfer');       //header函数是提交给表头的是一些下载的规格
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($local_file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($local_file));
            ob_clean();   //ob_clean这个函数的作用就是用来丢弃输出缓冲区中的内容,如果你的网站有许多生成的文件,那么想要访问正确,就要经常清除缓冲区
            ob_implicit_flush();//强制每当有输出的时候,即刻把输出发送到浏览器
            flush();   //ob_flush()和flush()的区别。前者是把数据从PHP的缓冲中释放出来,后者是把不在缓冲中的或者说是被释放出来的数据发送到浏览器。所以当缓冲存在的时候，我们必须ob_flush()和flush()同时使用。

            $file = fopen($local_file, "rb");  //打开指定的文件,r 代表只读,如果找不到，返回false
            while(!feof($file))  //判断是否存在
            {
                // send the current file part to the browser
                echo fread($file, round(100 * 1024));  //先顶下载速度为3MB
                ob_flush(); // 刷新PHP缓冲区到Web服务器
                // flush the content to the browser
                flush();  //传给浏览器
                // sleep one second
                sleep(1);  //等待1秒
            }
            fclose($file);  //关闭文件
        }else{
            echo "123";
        }
    }

    public function web()
    {
        return $this->view->fetch();
    }

    function python()
    {
        $a = '5';
        $adta = exec("E:\python/test.py {$a}",$out,$res);
        print_r($out);
        echo "<br>";
        echo '外部程序运行是否成功:'.$res."(0代表成功,1代表失败)";
    }

    function fn($n ,$m)
    {
        $arr = range(1,$n);

        $i = 0 ;    //设置数组指针
        while(count($arr)>1)
        {
            //遍历数组，判断当前猴子是否为出局序号，如果是则出局，否则放到数组最后
            if(($i+1) % $m ==0) {
                unset($arr[$i]);
            } else {
                //array_push() 函数向第一个参数的数组尾部添加一个或多个元素（入栈），然后返回新数组的长度。
                array_push($arr ,$arr[$i]); //本轮非出局猴子放数组尾部
                unset($arr[$i]);   //删除  array_push函数只是将元素复制一份挪到了尾部，所以原本的那个必须删除
            }
            $i++;
        }
        return $arr;
    }

    function worker()
    {
        // 指明给谁推送，为空表示向所有在线用户推送
        $to_uid = "";
        // 推送的url地址，上线时改成自己的服务器地址
        $push_api_url = "http://127.0.0.1:5678/";
        $post_data = array(
            "type" => "publish",
            "content" => "这个是推送的测试数据",
            "to" => 123,
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        var_export($return);
    }


}
