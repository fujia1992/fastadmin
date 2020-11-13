<?php
namespace app\admin\controller\kaoshi;

use app\common\controller\Backend;
use think\Db;
use think\Config;
/**
 *
 *
 * @icon fa fa-circle-o
 */
class Subject extends Backend
{

    /**
     * Subject模型对象
     * @var \app\admin\model\KaoshiSubject
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\KaoshiSubject;
        $config = Config::get('database2');   //读取第二个数据库配置
        $connect =  Db::connect($config);    //连接数据库
        $data = $connect->table('xy_user')->find();
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

}
