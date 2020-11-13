<?php

namespace addons\cms\controller\wxapp;

use addons\cms\library\CommentException;
use think\Config;
use think\Exception;

/**
 * 评论
 */
class Comment extends Base
{
    protected $noNeedLogin = ['index'];

    /**
     * 评论列表
     */
    public function index()
    {
        $aid = (int)$this->request->request('aid');
        $page = (int)$this->request->request('page');
        Config::set('paginate.page', $page);
        $commentList = \addons\cms\model\Comment::getCommentList(['aid' => $aid]);
        $commentList = $commentList->getCollection();
        foreach ($commentList as $index => $item) {
            if ($item->user && $item->user->avatar) {
                $item->user->avatar = cdnurl($item->user->avatar, true);
            }
        }
        $this->success('', ['commentList' => $commentList]);
    }

    /**
     * 发表评论
     */
    public function post()
    {
        try {
            $params = $this->request->post();
            \addons\cms\model\Comment::postComment($params);
        } catch (CommentException $e) {
            $this->success($e->getMessage(), ['token' => $this->request->token()]);
        } catch (Exception $e) {
            $this->error($e->getMessage(), ['token' => $this->request->token()]);
        }
        $this->success(__('评论成功'), ['token' => $this->request->token()]);
    }
}
