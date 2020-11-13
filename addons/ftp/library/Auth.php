<?php

namespace addons\ftp\library;

final class Auth
{
    public $off;  // 返回操作状态(成功/失败)
    public $conn_id;  // FTP连接

    /**
     * 方法：FTP连接
     * @FTP_HOST -- FTP主机
     * @FTP_PORT -- 端口
     * @FTP_USER -- 用户名
     * @FTP_PASS -- 密码
     * @FTP_SSH  -- 加密传输
     * @FTP_PASV -- 被动模式
     */
    function __construct($FTP_HOST, $FTP_PORT, $FTP_USER, $FTP_PASS, $FTP_SSH = 1, $FTP_PASV = 1)
    {
        if ($FTP_SSH) {
            $this->conn_id = @ftp_ssl_connect($FTP_HOST, $FTP_PORT);
            if (!$this->conn_id) throw new \Exception("FTP服务器连接失败:" . $FTP_HOST);
        } else {
            $this->conn_id = @ftp_connect($FTP_HOST, $FTP_PORT);
            if (!$this->conn_id) throw new \Exception("FTP服务器连接失败:" . $FTP_HOST);
        }

        $login = @ftp_login($this->conn_id, $FTP_USER, $FTP_PASS);
        ftp_pasv($this->conn_id, $FTP_PASV); // 打开被动模拟
        if (!$login) throw new \Exception("FTP服务器登陆失败");
    }

    /**
     * 方法：上传文件
     * @path-- 本地路径
     * @newpath -- 上传路径
     * @type-- 若目标目录不存在则新建
     */
    function up_file($path, $newpath, $type = true)
    {
        if ($type) $this->dir_mkdirs($newpath);
        $this->off = @ftp_put($this->conn_id, $newpath, $path, FTP_BINARY);
        if (!$this->off) throw new \Exception("FTP服务器上传失败");
    }

    /**
     * 方法：删除文件
     * @path -- 路径
     */
    function del_file($path)
    {
        $this->off = @ftp_delete($this->conn_id, $path);
    }

    /**
     * 方法：生成目录
     * @path -- 路径
     */
    function dir_mkdirs($path)
    {
        $path_arr = explode('/', $path);  // 取目录数组
        $file_name = array_pop($path_arr);// 弹出文件名
        $path_div = count($path_arr);// 取层数

        foreach ($path_arr as $val)// 创建目录
        {
            if ($val) {
                if (@ftp_chdir($this->conn_id, $val) == FALSE) {
                    $tmp = @ftp_mkdir($this->conn_id, $val);
                    if ($tmp == FALSE) {
                        throw new \Exception("目录创建失败,请检查权限及路径是否正确！");
                        exit;
                    }
                    @ftp_chdir($this->conn_id, $val);
                }
            }
        }

        for ($i = 1; $i <= $path_div; $i++)  // 回退到根
        {
            @ftp_cdup($this->conn_id);
        }
    }

    /**
     * 方法：关闭FTP连接
     */
    function close()
    {
        ftp_close($this->conn_id);
    }

}
