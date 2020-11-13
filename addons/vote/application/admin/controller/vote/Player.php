<?php

namespace app\admin\controller\vote;

use app\common\controller\Backend;

/**
 * 投票参赛管理
 *
 * @icon fa fa-circle-o
 */
class Player extends Backend
{

    /**
     * Player模型对象
     * @var \app\admin\model\vote\Player
     */
    protected $model = null;
    protected $subject = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\vote\Player;

        $subject_id = $this->request->param('subject_id', 0);
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign('subject_id', $subject_id);
        $this->assignconfig('subject_id', $subject_id);
    }

    /**
     * 查看
     */
    public function index()
    {
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $subject_id = $this->request->param('subject_id', 0);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->with(['user', 'subject', 'category'])
                ->where(function ($query) use ($subject_id) {
                    if ($subject_id) {
                        $query->where('player.subject_id', $subject_id);
                    }
                })
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['user', 'subject', 'category'])
                ->where(function ($query) use ($subject_id) {
                    if ($subject_id) {
                        $query->where('player.subject_id', $subject_id);
                    }
                })
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $index => $item) {
                $item->user->visible(['id', 'nickname']);
                $item->subject->visible(['id', 'title']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 导入
     */
    public function import()
    {
        return parent::import();
    }

    /**
     * 详情
     */
    public function detail()
    {
        $ids = $this->request->param('ids');
        $row = \app\admin\model\vote\Player::get($ids);
        if (!$row) {
            $this->error('未找到指定的用户');
        }
        $applydataList = (array)json_decode($row['applydata'], true);
        $fields = \app\admin\model\vote\Fields::where('subject_id', $row['subject_id'])->column('name,title');
        $fields = array_merge(['nickname' => '名称', 'intro' => '简介', 'content' => '详细介绍', 'image' => '图片', 'user_id' => '会员ID', 'number' => '序号', 'subject_id' => '主题ID'], $fields);
        $this->view->assign('row', $row);
        $this->view->assign('fields', $fields);
        $this->view->assign('applydataList', $applydataList);
        return $this->view->fetch();
    }
}
