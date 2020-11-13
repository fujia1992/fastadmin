<?php

namespace addons\ftp;

use app\common\library\Menu;
use app\common\model\Attachment;
use think\Addons;

/**
 * 插件
 */
class Ftp extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {

        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {

        return true;
    }

    /**
     *
     * @return mixed
     */
    public function uploadConfigInit(&$upload)
    {
        $uploadcfg = $this->getConfig('ftp');
        $uploadcfg = $uploadcfg ? $uploadcfg : [];
        $uploadcfg['ftp_usernmae'] = isset($uploadcfg['ftp_usernmae']) ? $uploadcfg['ftp_usernmae'] : '';
        $uploadcfg['ftp_password'] = isset($uploadcfg['ftp_password']) ? $uploadcfg['ftp_password'] : '';
        $uploadcfg['ftp_host'] = isset($uploadcfg['ftp_host']) ? $uploadcfg['ftp_host'] : '';
        $uploadcfg['ftp_port'] = isset($uploadcfg['ftp_port']) ? $uploadcfg['ftp_port'] : '';
        $uploadcfg['ftp_ssh'] = isset($uploadcfg['ftp_ssh']) ? $uploadcfg['ftp_ssh'] : '';
        $uploadcfg['cdn_url'] = isset($uploadcfg['cdn_url']) ? $uploadcfg['cdn_url'] : '';

        $upload['ftp_usernmae'] = isset($uploadcfg['ftp_usernmae']) ? $uploadcfg['ftp_usernmae'] : '';
        $upload['ftp_password'] = isset($uploadcfg['ftp_password']) ? $uploadcfg['ftp_password'] : '';
        $upload['ftp_host'] = isset($uploadcfg['ftp_host']) ? $uploadcfg['ftp_host'] : '';
        $upload['ftp_port'] = isset($uploadcfg['ftp_port']) ? $uploadcfg['ftp_port'] : '';
        $upload['ftp_ssh'] = isset($uploadcfg['ftp_ssh']) ? $uploadcfg['ftp_ssh'] : '';
        $upload['cdnurl'] = isset($uploadcfg['cdn_url']) ? $uploadcfg['cdn_url'] : '';
    }

    /**
     * 上传成功后
     */
    public function uploadAfter($attachment)
    {
        $config = $this->getConfig();

        if ($config['ftp_usernmae'] && $config['ftp_password']) {
            // 上传到 FTP 服务器
            if (strpos($attachment->url, '/uploads') === false) {
                $path = 'public/uploads';
            } else {
                $path = 'public';
            }
            try {
                $file = ROOT_PATH . $path . str_replace('/', DIRECTORY_SEPARATOR, $attachment->url);
                $FTP = new \addons\ftp\library\Auth($config['ftp_host'], $config['ftp_port'], $config['ftp_usernmae'], $config['ftp_password'], $config['ftp_ssh'], $config['ftp_pasv']);
                $FTP->up_file($file, $config['ftp_path'] . $attachment->url);
                // 是否删除源文件
                if ($config['delete_source'] && is_file($file)) {
                    @unlink($file);
                }
                Attachment::where('url', $attachment->url)->update(['storage' => 'ftp']);
                $FTP->close();
            } catch (\Exception $e) {
                echo json_encode(['code' => 0, 'msg' => $e->getMessage()]);
                exit;
            }
        }
    }

    /**
     * 删除成功后
     */
    public function uploadDelete($attachment)
    {
        $config = $this->getConfig();

        if ($config['ftp_usernmae'] && $config['ftp_password']) {
            // 同步删除 FTP 服务器
            $FTP = new \addons\ftp\library\Auth($config['ftp_host'], $config['ftp_port'], $config['ftp_usernmae'], $config['ftp_password'], $config['ftp_ssh'], $config['ftp_pasv']);
            $FTP->del_file($config['ftp_path'] . $attachment->url);
            $FTP->close();
        }
    }
}
