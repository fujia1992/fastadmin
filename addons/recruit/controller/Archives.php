<?php

namespace addons\recruit\controller;

use addons\recruit\model\News;

/**
 * 文档
 */
class Archives extends Base
{

    protected $noNeedLogin = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }

    /* 这里是banner 的详情数据 */
    public function BannerDetail(){
        $id = $this->request->request('id');
        $archives = News::get($id);
        $archives['image'] = cdnurl($archives['image'], true);
        $archives['updatetime'] = datetime($archives['updatetime']);

        //这里还要提取当前人的 报名历史
        $user_id = $this->auth->id;
        $Jobfair = \app\admin\model\Jobfair::where('user_id', $user_id)->where('block_id', $id)->order('updatetime', 'desc')->select();


        $this->success('', ['archivesInfo' => $archives, 'Jobfair'=> $Jobfair]);
    }

}
