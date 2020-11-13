<?php

namespace app\index\controller;

use addons\vote\model\Player;
use addons\vote\model\Record;
use addons\vote\model\Subject;
use app\common\controller\Frontend;

/**
 * 会员投票管理
 *
 * @icon fa fa-circle-o
 */
class Vote extends Frontend
{

    protected $noNeedRight = ["*"];
    protected $layout = 'default';

    protected $model = null;

    /**
     * 我创建的投票
     */
    public function subject()
    {
        $config = get_addon_config('vote');
        if (!in_array('subject', explode(',', $config['usersidebar']))) {
            $this->error("模块暂未开放");
        }
        $subjectList = Subject::where('user_id', $this->auth->id)->order('id', 'desc')->paginate(10);
        $this->view->assign("subjectList", $subjectList);
        $this->view->assign("title", "我创建的投票");
        return $this->view->fetch();
    }

    /**
     * 添加修改投票主题
     */
    public function post()
    {
        $config = get_addon_config('vote');
        if (!in_array('subject', explode(',', $config['usersidebar']))) {
            $this->error("模块暂未开放");
        }
        $subject = [];
        $id = $this->request->get('id/d');
        if ($id) {
            $subject = Subject::get($id);
            if (!$subject) {
                $this->error("未找到指定的主题");
            }
        }
        if ($subject && $subject['user_id'] != $this->auth->id) {
            $this->error("无法进行越权操作");
        }
        if ($subject && $subject['status'] == 'normal') {
            $this->error("已审核的投票无法进行修改");
        }
        if ($this->request->isPost()) {
            $token = $this->request->post('__token__');
            if (session('__token__') != $token) {
                $this->error("Token不正确！", null, ['token' => $this->request->token()]);
            }
            session('__token__', null);

            $row = $this->request->post("row/a", []);
            $row = array_diff_key($row, array_reverse(explode(',', $config['availablefields'])));
            $row['user_id'] = $this->auth->id;
            if ($subject) {
                $subject->allowField(true)->save($row);
                $this->success("更新成功", "index/vote/subject");
            } else {
                (new Subject())->allowField(true)->save($row);
                $this->success("添加成功", "index/vote/subject");

            }
        }
        $typeList = Subject::getTypeList();
        unset($typeList['array']);
        $this->view->assign('subject', $subject);
        $this->view->assign('typeList', $typeList);
        $this->view->assign("row", $subject);
        $this->view->assign("availableFields", explode(',', $config['availablefields']));
        $this->view->assign("title", $id ? "修改投票" : "添加投票");
        return $this->view->fetch();
    }

    /**
     * 我报名的投票
     */
    public function apply()
    {
        $config = get_addon_config('vote');
        if (!in_array('apply', explode(',', $config['usersidebar']))) {
            $this->error("模块暂未开放");
        }
        $player = Player::where('user_id', $this->auth->id)->order('id', 'desc')->paginate(10);
        $this->view->assign("playerList", $player);
        $this->view->assign("title", "我报名的投票");
        return $this->view->fetch();
    }

    /**
     * 我的投票记录
     */
    public function record()
    {
        $config = get_addon_config('vote');
        if (!in_array('record', explode(',', $config['usersidebar']))) {
            $this->error("模块暂未开放");
        }
        $recordList = Record::with(['player', 'subject'])->where('record.user_id', $this->auth->id)->order('record.id', 'desc')->paginate(10);
        $this->view->assign("recordList", $recordList);
        $this->view->assign("title", "我的投票记录");
        return $this->view->fetch();
    }

    /**
     * 检测元素是否可用
     * @internal
     */
    public function check_element_available()
    {
        $id = $this->request->request('id');
        $name = $this->request->request('name');
        $value = $this->request->request('value');
        $name = substr($name, 4, -1);
        if (!$name) {
            $this->error(__('Parameter %s can not be empty', 'name'));
        }

        $subject = Subject::getByDiyname($value);
        if ($id) {
            $subject = Subject::where('id', '<>', $id)->where('diyname', $value)->find();
        } else {
        }
        if ($subject) {
            $this->error("自定义名称暂不可用");
        }
        $this->success();
    }


}
