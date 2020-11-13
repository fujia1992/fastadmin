<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use think\Db;
use app\common\controller\Api;
/**
 *
 *
 * @icon fa fa-circle-o
 */
class Exams extends Api
{

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\examination\KaoshiExams;
        $this->typeList = ["1" => "单选题", "2" => "多选题", "3" => "判断题","4" => "填空题","5" => "简答题"];
    }
    /**
     * 查看所有考卷
     */
    public function index()
    {
        if ($this->request->isPost()) {
            $total = $this->model
                ->with(['subject', 'admin'])
                ->order("kaoshi_exams.id desc")
                ->count();

            $list = $this->model
                ->with(['subject', 'admin'])
                ->order("kaoshi_exams.id desc")
                ->limit('0', '10')
                ->select();

            foreach ($list as $row) {


            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
            $this->success('请求成功',$result);
        }else{
            $this->error('请用POST');
        }

    }


}
