<?php

namespace app\admin\controller\recruit;

use app\common\controller\Backend;

/**
 * 招聘会报名
 *
 * @icon fa fa-circle-o
 */
class Jobfair extends Backend
{
    
    /**
     * Jobfair模型对象
     * @var \app\admin\model\Jobfair
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Jobfair');

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(['recruitnews','user'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['recruitnews','user'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','block_id','block_title','user_id','tname','ttel','createtime']);
                $row->visible(['recruitnews']);
				$row->getRelation('recruitnews')->visible(['title']);
				$row->visible(['user']);
				$row->getRelation('user')->visible(['username','nickname']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
}
