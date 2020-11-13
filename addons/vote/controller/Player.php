<?php

namespace addons\vote\controller;

use addons\vote\model\Comment;
use addons\vote\model\Record;

class Player extends Base
{
    public function index()
    {
        $id = $this->request->param('id/d');
        $player = \addons\vote\model\Player::get($id);
        if (!$player || $player->status != 'normal') {
            if (!$this->auth->id || $player->user_id != $this->auth->id) {
                $this->error("未找到对应的参赛信息");
            }
        }
        $player->setData($player->applydata);
        $subject = \addons\vote\model\Subject::get($player->subject_id);

        $config = get_addon_config('vote');

        $commentList = Comment::with('user')
            ->where('player_id', $player->id)
            ->where('status', 'normal')
            ->order('id', isset($config['commentorderway']) ? $config['commentorderway'] : 'asc')
            ->paginate(isset($config['commentpagesize']) ? $config['commentpagesize'] : 10, false, ['fragment' => 'comment']);

        $ip = $this->getIp();
        $user_id = $this->auth->id;
        $voted = Record::where(function ($query) use ($user_id, $ip) {
            if ($user_id) {
                $query->where('user_id', $user_id);
            } else {
                $query->where('user_id', 0)->where('ip', $ip);
            }
        })->where('subject_id', $subject->id)
            ->where('player_id', $player->id)
            ->whereTime('createtime', 'today')
            ->count();

        $player->voted = $voted;
        $player->setInc("views");
        $this->view->assign('__subject__', $subject);
        $this->view->assign('__player__', $player);
        $this->view->assign('commentList', $commentList);
        $this->view->assign('title', $player->nickname . ' - ' . $subject->title);
        $template = ($subject->playertpl ? $subject->playertpl : 'player');
        $template = preg_replace('/\.html$/', '', $template);
        return $this->view->fetch('/' . $template);
    }

}
