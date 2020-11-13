<?php

namespace addons\vote\controller;

use addons\vote\library\Ip2Region;
use addons\vote\library\Jssdk;
use addons\vote\model\Comment;
use addons\vote\model\Record;
use fast\Pinyin;
use fast\Tree;
use think\Cache;
use think\Db;
use think\Exception;
use think\Session;

class Index extends Base
{

    public function index()
    {
        $this->error("请从后台管理->投票管理->投票主题管理<br>创建投票并获取链接", "");
    }

    /**
     * 投票
     */
    public function vote()
    {
        $player_id = $this->request->post("player_id/d");
        $player = \addons\vote\model\Player::get($player_id);
        if (!$player) {
            $this->error("未找到请求的参赛者");
        }
        $subject = $player->subject;
        if (!$subject || !in_array($subject->status, ['normal', 'notstarted', 'expired'])) {
            if (!$this->auth->id || $subject->user_id != $this->auth->id) {
                $this->error("未找到请求的投票");
            }
        }
        if ($subject->status == 'notstarted') {
            $this->error("投票暂未开始");
        } elseif ($subject->status == 'expired') {
            $this->error("投票已结束");
        }
        $user_id = $this->auth->id;
        $ip = $this->getIp();
        //限制登录后才可以投票
        if ($subject->needlogin && !$this->auth->id) {
            $this->error("请登录后再进行投票");
        }
        //只在微信中投票
        if ($subject->onlywechat && !$this->isWechat()) {
            $this->error("当前投票只对微信端开放");
        }
        //限制投票区域
        if ($subject->limitarea && !$this->isPrivateIp($ip)) {
            $region = new Ip2Region(ADDON_PATH . 'vote' . DS . 'data' . DS . 'ip2region.db');
            $location = function ($ip) use ($region) {
                $arr = $region->binarySearch($ip);
                return intval($arr['city_id']);
            };
            if (!in_array($location($ip), explode(',', $subject->limitarea))) {
                $this->error("当前投票地址区域限制");
            }
        }
        //限制每天投票次数
        $count = Record::where(function ($query) use ($user_id, $ip) {
            if ($user_id) {
                $query->where('user_id', $user_id);
            } else {
                $query->where('user_id', 0)->where('ip', $ip);
            }
        })
            ->whereTime('createtime', 'today')
            ->where('subject_id', $subject->id)
            ->count();
        if ($count >= $subject->pervotenums) {
            $this->error("每天最多可投{$subject->pervotenums}票");
        }
        //限制每天对同一选手次数
        $count = Record::where(function ($query) use ($user_id, $ip) {
            if ($user_id) {
                $query->where('user_id', $user_id);
            } else {
                $query->where('user_id', 0)->where('ip', $ip);
            }
        })
            ->where('subject_id', $subject->id)
            ->where('player_id', $player->id)
            ->whereTime('createtime', 'today')
            ->count();
        if ($count >= $subject->pervotelimit) {
            $this->error("每天最多对同一参选{$subject->playername}投{$subject->pervotelimit}票");
        }
        $voters = Record::where(function ($query) use ($user_id, $ip) {
            if ($user_id) {
                $query->where('user_id', $user_id);
            } else {
                $query->where('user_id', 0)->where('ip', $ip);
            }
        })
            ->where('subject_id', $subject->id)
            ->count();

        Db::startTrans();
        try {
            $data = [
                'subject_id' => $subject->id,
                'player_id'  => $player->id,
                'ip'         => $this->request->ip(),
                'user_id'    => (int)$this->auth->id
            ];
            Record::create($data);
            //更新投票总数
            $subject->setInc("votes");
            if ($voters == 0) {
                //更新投票人数
                $subject->setInc("voters");
            }
            //更新参赛者得票数
            $player->setInc('votes');
            $player->votetime = time();
            $player->save();
            Cache::rm("ranklist-" . $subject->id);
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error("投票失败,请重试");
        }
        $data = [
            'disabled' => $subject->pervotelimit == $count + 1 ? true : false
        ];
        $this->success("投票成功", null, $data);
    }

    /**
     * 发表评论
     */
    public function comment()
    {
        if (!$this->auth->id) {
            $this->error("请登录后再发表评论");
        }

        $player_id = $this->request->post("player_id/d");
        $player = \addons\vote\model\Player::get($player_id);
        if (!$player) {
            $this->error("未找到请求的参赛信息", null, ['token' => $this->request->token()]);
        }
        $subject = $player->subject;
        if (!$subject || !in_array($subject->status, ['normal', 'notstarted', 'expired'])) {
            if (!$this->auth->id || $subject->user_id != $this->auth->id) {
                $this->error("请找到请求的投票", null, ['token' => $this->request->token()]);
            }
        }
        if ($subject->status == 'expired') {
            $this->error("投票已结束");
        }
        if (!$subject->iscomment) {
            $this->error("该投票不允许发表评论", null, ['token' => $this->request->token()]);
        }
        $content = $this->request->post("content");
        if (!$content) {
            $this->error("评论内容不能为空", null, ['token' => $this->request->token()]);
        }
        $token = $this->request->post('__token__');
        if (!$token || $token != Session::get('__token__')) {
            $this->error("Token验证失败", null, ['token' => $this->request->token()]);
        }
        session('__token__', null);

        $config = get_addon_config('vote');

        $ip = $this->getIp();

        Db::startTrans();
        try {
            $data = [
                'subject_id' => $subject->id,
                'player_id'  => $player->id,
                'user_id'    => $this->auth->id,
                'content'    => $content,
                'ip'         => $ip,
                'status'     => $config['iscommentaudit'] ? 'hidden' : 'normal'
            ];
            Comment::create($data);
            $player->setInc('comments');
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error("评论失败，请重试", null, ['token' => $this->request->token()]);
        }
        $this->success("评论成功");
    }

    /**
     * 分享
     */
    public function share()
    {
        $url = $this->request->request("url", "", "trim");
        $jssdk = new Jssdk();
        $package = $jssdk->getSignedPackage($url);
        $this->success("请求成功", "", $package);
        return;
    }

    public function json()
    {
        $list = Db::table("area")->where('level', '<>', 4)->where('name', 'not like', '%县级行政区划%')->select();
        $list = Tree::instance()->init($list)->getTreeArray(1);
        $data = [];
        foreach ($list as $index => $item) {
//            $data[] = [
//                "id"        => $item['id'],
//                "name"      => $item['name'],
//                "parentId"  => $item['pid'],
//                "shortName" => $item['name'],
//                "letter"    => ucfirst(substr(Pinyin::get($item['name'], true), 0, 1)),
//                "cityCode"  => $item['id'],
//                "pinyin"    => ucfirst(Pinyin::get($item['name'], false))
//            ];
            foreach ($item['childlist'] as $index => $value) {
                $data[] = [
                    "id"        => $value['id'],
                    "name"      => $value['name'],
                    "parentId"  => $value['pid'],
                    "shortName" => $value['name'],
                    "letter"    => ucfirst(substr(Pinyin::get($value['name'], true), 0, 1)),
                    "cityCode"  => $value['id'],
                    "pinyin"    => ucfirst(Pinyin::get($value['name'], false))
                ];
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        return;
    }

}
