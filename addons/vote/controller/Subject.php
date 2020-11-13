<?php

namespace addons\vote\controller;

use addons\vote\model\Category;
use addons\vote\model\Player;
use addons\vote\model\Record;

class Subject extends Base
{
    public function index()
    {
        $diyname = $this->request->param('diyname');
        $subject = \addons\vote\model\Subject::getByDiyname($diyname);
        if (!$subject || !in_array($subject->status, ['normal', 'notstarted', 'expired'])) {
            if (!$this->auth->id || $subject->user_id != $this->auth->id) {
                $this->error("未找到请求的投票");
            }
        }
        $q = $this->request->get('q');
        $category = $this->request->get('category/d');
        // 刷新主题状态
        if ($subject->status == 'expired' && $subject->getData('status') == 'normal') {
            $subject->save(['status' => $subject->status]);
        }
        // 参赛者列表
        $playerList = Player::where('subject_id', $subject->id)
            ->where('status', '<>', 'hidden')
            ->where(function ($query) use ($category) {
                if ($category) {
                    $query->where('category_id', $category);
                }
            })
            ->where(function ($query) use ($q) {
                if ($q) {
                    $query->where('id', $q)->whereOr('nickname', 'like', '%' . $q . '%');
                }
            })
            ->order('id', 'asc')
            ->paginate($subject->pagesize, false, [
                'query'    => $category ? ['category' => $category] : [],
                'fragment' => 'players'
            ]);

        $user_id = $this->auth->id;
        $ip = $this->getIp();

        // 已投票列表
        $votedList = Record::where(function ($query) use ($user_id, $ip) {
            if ($user_id) {
                $query->where('user_id', $user_id);
            } else {
                $query->where('user_id', 0)->where('ip', $ip);
            }
        })->where('subject_id', $subject->id)
            ->whereTime('createtime', 'today')
            ->field("player_id, COUNT(*) AS votes")
            ->group('player_id')
            ->select();

        $playerVotedList = [];
        foreach ($votedList as $index => $item) {
            $playerVotedList[$item['player_id']] = $item['votes'];
        }
        foreach ($playerList as $index => $item) {
            $item->voted = isset($playerVotedList[$item['id']]) ? $playerVotedList[$item['id']] : 0;
        }
        $subject->setInc('views');

        // 分类列表
        $categoryList = Category::getSubjectCategoryList($subject->id);

        $this->view->assign('__subject__', $subject);
        $this->view->assign('playerList', $playerList);
        $this->view->assign('categoryList', $categoryList);
        $this->view->assign('title', $subject->title);
        $template = ($subject->subjecttpl ? $subject->subjecttpl : 'subject');
        $template = preg_replace('/\.html$/', '', $template);
        return $this->view->fetch('/' . $template);
    }

}
