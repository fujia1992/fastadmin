<?php

namespace app\admin\controller\vote;

use app\common\controller\Backend;

/**
 * 投票主题管理
 *
 * @icon fa fa-circle-o
 */
class Subject extends Backend
{

    /**
     * Subject模型对象
     * @var \app\admin\model\vote\Subject
     */
    protected $model = null;
    protected $multiFields = 'status,iscomment,needlogin,onlywechat';
    protected $noNeedRight = ['get_template_list', 'check_element_available'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\vote\Subject;
        $this->view->assign("statusList", $this->model->getStatusList());
    }


    /**
     * 获取模板列表
     * @internal
     */
    public function get_template_list()
    {
        $files = [];
        $keyValue = $this->request->request("keyValue");
        if (!$keyValue) {
            $type = $this->request->request("type");
            $name = $this->request->request("name");
            if ($name) {
                //$files[] = ['name' => $name . '.html'];
            }
            //设置过滤方法
            $this->request->filter(['strip_tags']);
            $config = get_addon_config('vote');
            $themeDir = ADDON_PATH . 'vote' . DS . 'view' . DS;
            $dh = opendir($themeDir);
            while (false !== ($filename = readdir($dh))) {
                if ($filename == '.' || $filename == '..') {
                    continue;
                }
                if ($type) {
                    $rule = $type;
                    if (!preg_match("/^{$rule}(.*)/i", $filename)) {
                        continue;
                    }
                }
                $files[] = ['name' => $filename];
            }
        } else {
            $files[] = ['name' => $keyValue];
        }
        return $result = ['total' => count($files), 'list' => $files];
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
        if ($name == 'diyname') {
            if ($id) {
                $this->model->where('id', '<>', $id);
            }
            $exist = $this->model->where($name, $value)->find();
            if ($exist) {
                $this->error(__('The data already exist'));
            } else {
                $this->success();
            }
        }
    }
}
