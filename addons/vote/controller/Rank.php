<?php

namespace addons\vote\controller;

use addons\vote\model\Category;

class Rank extends Base
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
        $category = $this->request->param('category/d');
        $rankList = \addons\vote\model\Player::where('subject_id', $subject->id)
            ->where(function ($query) use ($category) {
                if ($category) {
                    $query->where('category_id', $category);
                }
            })
            ->where('status', '<>', 'hidden')
            ->order('votes DESC,votetime ASC')
            ->select();
        $this->view->assign('__subject__', $subject);

        $categoryList = Category::getSubjectCategoryList($subject->id);
        $this->view->assign('categoryList', $categoryList);
        $this->view->assign('rankList', $rankList);
        $this->view->assign('title', "排行榜 - {$subject->title}");
        $template = ($subject->ranktpl ? $subject->ranktpl : 'rank');
        $template = preg_replace('/\.html$/', '', $template);
        return $this->view->fetch('/' . $template);
    }

}
