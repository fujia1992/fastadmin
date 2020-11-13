<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use Firebase\JWT\JWT;
use \think\Loader;
use Workerman\Worker;
use think\Db;
use think\Cookie;


class Mp4 extends Frontend
{
    public function index()
    {
        return $this->view->fetch();
    }

    public function mp4()
    {
        $url = ROOT_PATH.'/public/uploads/20201020/30150266524de77c979cd2617b4d9d14.mp4';
        $this->GetMp4File($url);
        die();
    }

    function GetMp4File($localfile) {
        $size = filesize($localfile);
        $start = 0;
        $end = $size - 1;
        $length = $size;

        header("Accept-Ranges: 0-$size");
        header("Content-Type: video/mp4");

        $ranges_arr = array();
        if (isset($_SERVER['HTTP_RANGE'])) {
            if (!preg_match('/^bytes=\d*-\d*(,\d*-\d*)*$/i', $_SERVER['HTTP_RANGE'])) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
            }
            $ranges = explode(',', substr($_SERVER['HTTP_RANGE'], 6));
            foreach ($ranges as $range) {
                $parts = explode('-', $range);
                $ranges_arr[] = array($parts[0],$parts[1]);
            }

            $ranges = $ranges_arr[0];
            if($ranges[0]==''){
                if($ranges[1]!=''){
                    //Range: bytes=-n 表示取文件末尾的n个字节
                    $length = (int)$ranges[1];
                    $start = $size - $length;
                }else{
                    //Range: bytes=- 这种形式不合法
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                }
            }else{
                $start = (int)$ranges[0];
                if($ranges[1]!=''){
                    //Range: bytes=n-m 表示从文件的n偏移量读到m偏移量
                    $end = (int)$ranges[1];
                }
                //Range: bytes=n- 表示从文件的n偏移量读到末尾
                $length = $end - $start + 1;
            }
            header('HTTP/1.1 206 PARTIAL CONTENT');
        }

        header("Content-Range: bytes {$start}-{$end}/{$size}");
        header("Content-Length: $length");

        $buffer = 8096;
        $file = fopen($localfile, 'rb');
        if($file){
            fseek($file, $start);
            while (!feof($file) && ($p = ftell($file)) <= $end){
                if ($p + $buffer > $end) {
                    $buffer = $end - $p + 1;
                }
                set_time_limit(0);
                echo fread($file, $buffer);
                flush();
            }
            fclose($file);
        }
    }
}
?>