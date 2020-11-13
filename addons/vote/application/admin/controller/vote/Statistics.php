<?php

namespace app\admin\controller\vote;

use addons\vote\model\SearchLog;
use app\admin\model\User;
use app\common\controller\Backend;

/**
 * 统计控制台管理
 *
 * @icon fa fa-bar-chart
 */
class Statistics extends Backend
{

    /**
     * Archives模型对象
     */
    protected $model = null;
    protected $noNeedRight = [];
    protected $isSuperAdmin = false;
    protected $searchFields = 'id,title';
    protected $subject = null;

    /**
     * 查看
     */
    public function index()
    {
        $config = get_addon_config('vote');
        $this->view->assign("comments", 343223);

        $subject_id = $this->request->param('subject_id');
        $subject = \app\admin\model\vote\Subject::get($subject_id);
        if (!$subject) {
            $this->error("未找到请求的投票主题");
        }
        $this->subject = $subject;
        if ($this->request->isPost()) {
            $date = $this->request->post('date', '');
            $type = $this->request->post('type', '');

            if ($type == 'vote' || $type == 'trend') {
                list($category, $data) = $this->getVoteStatisticsData($date);
                $statistics = ['category' => $category, 'data' => $data];
                $this->success('', '', $statistics);
            } elseif ($type == 'cate') {
                list($category, $data) = $this->getCateStatisticsData($date);
                $statistics = ['category' => $category, 'data' => $data];
                $this->success('', '', $statistics);
            }
        }

        $playerVotesList = \app\admin\model\vote\Player::where('subject_id', $subject['id'])->order("votes DESC,votetime ASC")->limit(10)->select();
        $playerViewsList = \app\admin\model\vote\Player::where('subject_id', $subject['id'])->order("views DESC,votetime ASC")->limit(10)->select();
        $playerCommentsList = \app\admin\model\vote\Player::where('subject_id', $subject['id'])->order("comments DESC,votetime ASC")->limit(10)->select();

        $this->view->assign("playerVotesList", $playerVotesList);
        $this->view->assign("playerViewsList", $playerViewsList);
        $this->view->assign("playerCommentsList", $playerCommentsList);


        //管理员发文统计图表
        list($category, $data) = $this->getVoteStatisticsData('');
        $this->assignconfig('voteListCategory', $category);
        $this->assignconfig('voteListData', $data);

        //管理员发文统计图表
        list($category, $data) = $this->getCateStatisticsData('');
        $this->assignconfig('cateListCategory', $category);
        $this->assignconfig('cateListData', $data);

        $unsettlePlayer = \app\admin\model\vote\Player::where('subject_id', $subject['id'])->where('status', 'hidden')->count();
        $unsettleComment = \app\admin\model\vote\Comment::where('subject_id', $subject['id'])->where('status', 'hidden')->count();
        $totalComments = \app\admin\model\vote\Comment::where('subject_id', $subject['id'])->count();

        $todayVotes = \app\admin\model\vote\Record::where('subject_id', $subject['id'])->whereTime('createtime', 'today')->count();
        $todayVoters = \app\admin\model\vote\Record::where('subject_id', $subject['id'])->whereTime('createtime', 'today')->count("DISTINCT user_id");

        $this->view->assign("todayVotes", $todayVotes);
        $this->view->assign("todayVoters", $todayVoters);
        $this->view->assign("unsettlePlayer", $unsettlePlayer);
        $this->view->assign("unsettleComment", $unsettleComment);
        $this->view->assign("totalComments", $totalComments);
        $this->view->assign("subject", $subject);
        return $this->view->fetch();
    }

    /**
     * 获取投票统计数据
     * @param string $date
     * @return array
     */
    protected function getVoteStatisticsData($date = '')
    {
        if ($date) {
            list($start, $end) = explode(' - ', $date);

            $starttime = strtotime($start);
            $endtime = strtotime($end);
        } else {
            $starttime = \fast\Date::unixtime('day', 0, 'begin');
            $endtime = \fast\Date::unixtime('day', 0, 'end');
        }
        $totalseconds = $endtime - $starttime;

        $format = '%Y-%m-%d';
        if ($totalseconds > 86400 * 30 * 2) {
            $format = '%Y-%m';
        } else {
            if ($totalseconds > 86400) {
                $format = '%Y-%m-%d';
            } else {
                $format = '%H:00';
            }
        }
        $orderList = \app\admin\model\vote\Record::where('createtime', 'between time', [$starttime, $endtime])
            ->where('subject_id', $this->subject->id)
            ->field('createtime, COUNT(*) AS nums, MIN(createtime) AS min_createtime, MAX(createtime) AS max_createtime, 
            DATE_FORMAT(FROM_UNIXTIME(createtime), "' . $format . '") AS create_date')
            ->group('create_date')
            ->select();

        if ($totalseconds > 84600 * 30 * 2) {
            $starttime = strtotime('last month', $starttime);
            while (($starttime = strtotime('next month', $starttime)) <= $endtime) {
                $column[] = date('Y-m', $starttime);
            }
        } else {
            if ($totalseconds > 86400) {
                for ($time = $starttime; $time <= $endtime;) {
                    $column[] = date("Y-m-d", $time);
                    $time += 86400;
                }
            } else {
                for ($time = $starttime; $time <= $endtime;) {
                    $column[] = date("H:00", $time);
                    $time += 3600;
                }
            }
        }
        $list = array_fill_keys($column, 0);
        foreach ($orderList as $k => $v) {
            $list[$v['create_date']] = round($v['nums'], 2);
        }
        $category = array_keys($list);
        $data = array_values($list);
        return [$category, $data];

    }

    /**
     * 获取订单占比统计数据
     * @param string $date
     * @return array
     */
    protected function getCateStatisticsData($date = '')
    {
        $starttime = \fast\Date::unixtime();
        $endtime = \fast\Date::unixtime('day', 0, 'end');
        $modelList = [];
        $cateListCategory = $cateListAmount = $cateListNums = [];
        $list = \app\admin\model\vote\Record::with(['player'])
            ->where('record.subject_id', $this->subject->id)
            ->where('record.createtime', 'between time', [$starttime, $endtime])
            ->field("player.category_id,COUNT(*) as nums")
            ->group('player.category_id')
            ->select();
        foreach ($list as $index => $item) {
            $modelList[$item['player']['category']['name']] = $item['nums'];
            $name = $item['player']['category']['name'];
            $name = $name ? $name : "其它";
            $cateListCategory[] = $name;
            $cateListNums[] = ['value' => $item['nums'], 'name' => $name];
        }
        if (!$cateListCategory) {
            $cateListCategory = [""];
            $cateListNums = [['value' => 0, 'name' => '投票数']];
        }
        return [$cateListCategory, $cateListNums];
    }

}
