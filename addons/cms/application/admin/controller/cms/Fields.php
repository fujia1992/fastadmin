<?php

namespace app\admin\controller\cms;

use app\common\controller\Backend;
use app\common\model\Config;

/**
 * 模型字段表
 *
 * @icon fa fa-circle-o
 */
class Fields extends Backend
{

    /**
     * Fields模型对象
     */
    protected $model = null;
    protected $modelValidate = true;
    protected $modelSceneValidate = true;

    protected $noNeedRight = ['rulelist'];
    protected $multiFields = 'isfilter,iscontribute';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\cms\Fields;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign('typeList', Config::getTypeList());
        $this->view->assign('regexList', Config::getRegexList());
    }

    /**
     * 查看
     */
    public function index()
    {
        $model_id = $this->request->param('model_id', 0);
        $diyform_id = $this->request->param('diyform_id', 0);
        $condition = $model_id ? ['model_id' => $model_id] : ['diyform_id' => $diyform_id];
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($condition)
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($condition)
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $nameArr = [
                'id'          => '主键',
                'user_id'     => '会员ID',
                'channel_id'  => '栏目ID',
                'channel_ids' => '副栏目ID集合',
                'model_id'    => '模型ID',
                'admin_id'    => '管理员ID',
                'special_ids' => '专题ID集合',
                'title'       => '标题',
                'seotitle'    => 'SEO标题',
                'style'       => '样式',
                'flag'        => '标志',
                'image'       => '缩略图',
                'images'      => '组图',
                'content'     => '内容',
                'keywords'    => '关键字',
                'description' => '描述',
                'isguest'     => '是否游客可访问',
                'iscomment'   => '是否允许评论',
                'tags'        => '标签',
                'weigh'       => '权重',
                'views'       => '浏览次数',
                'comments'    => '评论次数',
                'likes'       => '点赞次数',
                'dislikes'    => '点踩次数',
                'diyname'     => '自定义名称',
                'createtime'  => '创建时间',
                'updatetime'  => '更新时间',
                'publishtime' => '发布时间',
                'deletetime'  => '删除时间',
                'memo'        => '备注',
                'status'      => '状态'
            ];
            if ($model_id) {
                $model = \app\admin\model\cms\Modelx::get($model_id);
                if (!$model) {
                    $this->error("模型未找到");
                }
                $setting = $model->setting;
                $list = collection($list)->toArray();

                $list[] = [
                    'id'           => 'content',
                    'state'        => false,
                    'model_id'     => $model_id,
                    'diyform_id'   => '-',
                    'name'         => 'content',
                    'title'        => isset($nameArr['content']) ? $nameArr['content'] : '',
                    'type'         => 'text',
                    'issystem'     => true,
                    'isfilter'     => 0,
                    'iscontribute' => isset($setting['contibutefields']) && is_array($setting['contibutefields']) && in_array('content', $setting['contibutefields']),
                    'status'       => 'normal',
                    'createtime'   => 0,
                    'updatetime'   => 0
                ];
                $tableInfoList = \think\Db::name('cms_archives')->getTableInfo();
                $tableInfoList['fields'] = array_reverse($tableInfoList['fields']);
                foreach ($tableInfoList['fields'] as $index => $field) {
                    $type = isset($tableInfoList['type'][$field]) ? substr($tableInfoList['type'][$field], 0, stripos($tableInfoList['type'][$field], '(')) : 'unknown';
                    $item = [
                        'id'           => $field,
                        'state'        => false,
                        'model_id'     => $model_id,
                        'diyform_id'   => '-',
                        'name'         => $field,
                        'title'        => isset($nameArr[$field]) ? $nameArr[$field] : '',
                        'type'         => $type,
                        'issystem'     => true,
                        'isfilter'     => 0,
                        'iscontribute' => isset($setting['contibutefields']) && is_array($setting['contibutefields']) && in_array($field, $setting['contibutefields']),
                        'status'       => 'normal',
                        'createtime'   => 0,
                        'updatetime'   => 0
                    ];
                    $list[] = $item;
                }
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->assignconfig('model_id', $model_id);
        $this->assignconfig('diyform_id', $diyform_id);
        $this->view->assign('model_id', $model_id);
        $this->view->assign('diyform_id', $diyform_id);

        $model = $model_id ? \app\admin\model\cms\Modelx::get($model_id) : \app\admin\model\cms\Diyform::get($diyform_id);
        $this->view->assign('model', $model);
        $modelList = $model_id ? \app\admin\model\cms\Modelx::all() : \app\admin\model\cms\Diyform::all();
        $this->view->assign('modelList', $modelList);

        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        $model_id = $this->request->param('model_id', 0);
        $diyform_id = $this->request->param('diyform_id', 0);
        $this->view->assign('model_id', $model_id);
        $this->view->assign('diyform_id', $diyform_id);
        return parent::add();
    }

    /**
     * 批量操作
     * @param string $ids
     */
    public function multi($ids = "")
    {
        $params = $this->request->request('params');
        parse_str($params, $paramsArr);
        if (isset($paramsArr['isfilter'])) {
            $field = \app\admin\model\cms\Fields::get($ids);
            if (!$field || !in_array($field['type'], ['radio', 'checkbox', 'select', 'selects', 'array'])) {
                $this->error('只有类型为单选、复选、下拉列表、数组才可以加入列表筛选');
            }
        }
        if (isset($paramsArr['iscontribute']) && !is_numeric($ids)) {
            if (!$ids || !in_array($ids, ["image", "images", "tags", "content", "keywords", "description"])) {
                $this->error('参数错误');
            }
            $model_id = $this->request->param('model_id', 0);
            $model = \app\admin\model\cms\Modelx::get($model_id);
            if (!$model) {
                $this->error("模型未找到");
            }
            $setting = $model['setting'];
            $contibutefields = isset($setting['contibutefields']) ? $setting['contibutefields'] : [];
            if ($paramsArr['iscontribute']) {
                $contibutefields[] = $ids;
            } else {
                $contibutefields = array_values(array_diff($contibutefields, [$ids]));
            }
            $setting['contibutefields'] = $contibutefields;
            $model->setting = $setting;
            $model->save();
            $this->success("");
        }
        return parent::multi($ids);
    }

    /**
     * 规则列表
     * @internal
     */
    public function rulelist()
    {
        //主键
        $primarykey = $this->request->request("keyField");
        //主键值
        $keyValue = $this->request->request("keyValue", "");

        $keyValueArr = array_filter(explode(',', $keyValue));
        $regexList = Config::getRegexList();
        $list = [];
        foreach ($regexList as $k => $v) {
            if ($keyValueArr) {
                if (in_array($k, $keyValueArr)) {
                    $list[] = ['id' => $k, 'name' => $v];
                }
            } else {
                $list[] = ['id' => $k, 'name' => $v];
            }
        }
        return json(['list' => $list]);
    }
}
