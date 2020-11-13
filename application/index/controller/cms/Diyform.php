<?php

namespace app\index\controller\cms;

use app\common\controller\Frontend;
use addons\cms\model\Diyform as DiyformModel;

/**
 * 自定义表单
 */
class Diyform extends Frontend
{
    protected $layout = 'default';
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    /**
     * 表单
     */
    public function index()
    {
        $diyname = $this->request->param('diyname');
        if ($diyname && !is_numeric($diyname)) {
            $diyform = DiyformModel::getByDiyname($diyname);
        } else {
            $id = $diyname ? $diyname : $this->request->get('id', '');
            $diyform = DiyformModel::get($id);
        }
        if (!$diyform || $diyform['status'] == 'hidden') {
            $this->error(__('表单未找到'));
        }
        $fields = DiyformModel::getDiyformFields($diyform['id']);
        $this->view->assign('diyform', $diyform);
        $this->view->assign('fields', $fields);

        return $this->view->fetch();
    }
}
