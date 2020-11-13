<?php

namespace addons\recruit\controller;

use app\common\model\Addon;
use app\admin\model\Opencity;
use think\Config;

/**
 * 公共
 */
class Common extends Base
{

    protected $noNeedLogin = '*';

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 初始化
     */
    public function init()
    {
        //配置城市信息
        $city = [];
        $city = Opencity::field('id,city,weigh')->order('weigh ASC,id ASC')->select();

        //配置信息
        $upload = Config::get('upload');
        $upload['cdnurl'] = $upload['cdnurl'] ? $upload['cdnurl'] : cdnurl('', true);
        $upload['uploadurl'] = $upload['uploadurl'] == 'ajax/upload' ? cdnurl('/index/ajax/upload', true) : $upload['cdnurl'];
        $config = [
            'upload' => $upload
        ];

        $data = [
            'city'           => $city,
            'config'         => $config
        ];
        $this->success('', $data);

    }


}
