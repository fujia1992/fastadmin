<?php

namespace app\admin\controller\vote;

use app\admin\model\Admin;
use app\admin\model\AuthGroupAccess;
use app\common\controller\Backend;
use app\admin\model\vote\Category as CategoryModel;
use fast\Tree;
use think\Exception;

/**
 * 栏目表
 *
 * @icon fa fa-list
 */
class Category extends Backend
{
    protected $categoryList = [];
    protected $modelList = [];
    protected $multiFields = ['weigh', 'status'];

    /**
     * Category模型对象
     */
    protected $model = null;
    protected $noNeedRight = ['selectpage'];
    protected $subject = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->request->filter(['strip_tags']);
        $this->model = new \app\admin\model\vote\Category;

        $subject_id = $this->request->param('subject_id');
        $subject = \app\admin\model\vote\Subject::get($subject_id);
        if (!$subject) {
            $this->error("未找到请求的投票主题");
        }
        $this->subject = $subject;


        $tree = Tree::instance();
        $tree->init(collection($this->model->where('subject_id', $subject->id)->order('weigh desc,id desc')->select())->toArray(), 'pid');
        $this->categoryList = $tree->getTreeList($tree->getTreeArray(0), 'name');

        $this->view->assign("categoryList", $this->categoryList);
        $this->view->assign("statusList", CategoryModel::getStatusList());
        $this->view->assign("subject", $subject);
        $this->assignconfig('subject_id', $subject->id);
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $search = $this->request->request("search");
            $subject_id = $this->request->request("subject_id");
            //构造父类select列表选项数据
            $list = [];
            if ($search) {
                foreach ($this->categoryList as $k => $v) {
                    if (stripos($v['name'], $search) !== false || stripos($v['nickname'], $search) !== false) {
                        $list[] = $v;
                    }
                }
            } else {
                $list = $this->categoryList;
            }
            foreach ($list as $index => $item) {
                if ($subject_id && $subject_id != $item['subject_id']) {
                    unset($list[$index]);
                }
            }
            $list = array_values($list);
            $total = count($list);
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $nameArr = array_filter(explode("\n", str_replace("\r\n", "\n", $params['name'])));
                    if (count($nameArr) > 1) {
                        foreach ($nameArr as $index => $item) {
                            $params['name'] = $item;
                            $result = $this->model->allowField(true)->isUpdate(false)->data($params)->save();
                        }
                    } else {
                        $result = $this->model->allowField(true)->save($params);
                    }
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($this->model->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * Selectpage搜索
     *
     * @internal
     */
    public function selectpage()
    {
        return parent::selectpage();
    }

}
