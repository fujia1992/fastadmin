<?php

namespace app\admin\controller\kaoshi\examination;

use app\common\controller\Backend;
use think\Db;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class UserExams extends Backend {

	/**
	 * UserExams模型对象
	 * @var \app\admin\model\kaoshi\examination\KaoshiUserExams
	 */
	protected $model = null;

	public function _initialize() {
		parent::_initialize();
		$this->model = new \app\admin\model\kaoshi\examination\KaoshiUserExams;
		$this->view->assign("statusList", $this->model->getStatusList());
	}

	/**
	 * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
	 * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
	 * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
	 */

	/*
		    *获取计划参与人员详情
	*/
	public function users($ids) {
		$this->request->filter(['strip_tags']);
		if ($this->request->isAjax()) {
			$map = [
				'a.plan_id' => $ids,
			];
			$field = [
				'a.user_id',
				'b.plan_name',
				'c.nickname', 'c.username', 'c.id',
				'e.status', 'e.lasttime', 'e.starttime', 'e.score',
			];
			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
			$user_exam = Db::name('KaoshiUserExams')->field('max(lasttime) as lasttime, min(starttime) as starttime, max(score) as score, status, user_plan_id, id')->group('user_plan_id')->buildSql();

			$total = Db::name('KaoshiUserPlan')->alias('a')
				->join('__KAOSHI_PLAN__ b', 'b.id = a.plan_id', 'left')
				->join('__USER__ c', 'c.id = a.user_id', 'left')
				->join([$user_exam => 'e'], 'e.user_plan_id = a.id', 'left')
				->field($field)
				->where($map)
				->group('a.user_id')
				->count();
			$list = Db::name('KaoshiUserPlan')->alias('a')
				->join('__KAOSHI_PLAN__ b', 'b.id = a.plan_id', 'left')
				->join('__USER__ c', 'c.id = a.user_id', 'left')
				->join([$user_exam => 'e'], 'e.user_plan_id = a.id', 'left')
				->field($field)
				->where($map)
				->group('a.user_id')
				->limit($offset, $limit)
				->select();

			$list = collection($list)->toArray();

			foreach ($list as $key => $value) {

			}

			$result = array("total" => $total, "rows" => $list);

			return json($result);
		}
		return $this->view->fetch();

	}

	public function examrank() {
		$this->request->filter(['strip_tags']);
		if ($this->request->isAjax()) {
			$type = 0;
			list($where, $sort, $order, $offset, $limit) = $this->buildparams();

			$maxscore = $this->model->alias('a')
				->join('__KAOSHI_USER_PLAN__ b', 'b.id = a.user_plan_id')
				->join('__KAOSHI_PLAN__ c', 'c.id = b.plan_id')
				->join('__USER__ d', 'd.id = b.user_id')
				->field('max(a.score) as maxscore, c.plan_name, d.nickname,a.user_plan_id, b.plan_id,b.user_id')
				->where(['c.type' => $type, 'a.status' => 1, 'c.deletetime' => NULL])
				->group('a.user_plan_id')
				->order('maxscore desc')
				->buildSql();

			$rank = Db::name('User')->alias('u')
				->join([$maxscore => 'm'], 'u.id = m.user_id')
				->field('sum(m.maxscore) as score,m.*,u.id')
				->group('m.user_id')
				->order('score desc')
				->limit($offset, $limit)
				->select();

			foreach ($rank as $key => $value) {
				$rank[$key]['ranking'] = intval($key) + 1;
			}

			$result = array("total" => count($rank), "rows" => $rank);

			return json($result);
		}

		return $this->view->fetch();
	}

	public function studyrank() {
		$this->request->filter(['strip_tags']);
		if ($this->request->isAjax()) {
			$type = 1;
			list($where, $sort, $order, $offset, $limit) = $this->buildparams();

			$maxscore = $this->model->alias('a')
				->join('__KAOSHI_USER_PLAN__ b', 'b.id = a.user_plan_id')
				->join('__KAOSHI_PLAN__ c', 'c.id = b.plan_id')
				->join('__USER__ d', 'd.id = b.user_id')
				->field('max(a.score) as maxscore, c.plan_name, d.nickname,a.user_plan_id, b.plan_id,b.user_id')
				->where(['c.type' => $type, 'a.status' => 1, 'c.deletetime' => NULL])
				->group('a.user_plan_id')
				->order('maxscore desc')
				->buildSql();

			$rank = Db::name('User')->alias('u')
				->join([$maxscore => 'm'], 'u.id = m.user_id')
				->field('sum(m.maxscore) as score,m.*,u.id')
				->group('m.user_id')
				->order('score desc')
				->limit($offset, $limit)
				->select();

			foreach ($rank as $key => $value) {
				$rank[$key]['ranking'] = intval($key) + 1;
			}

			$result = array("total" => count($rank), "rows" => $rank);

			return json($result);
		}

		return $this->view->fetch();
	}

}
