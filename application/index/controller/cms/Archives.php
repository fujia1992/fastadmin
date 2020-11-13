<?php

namespace app\index\controller\cms;

use addons\cms\library\Service;
use addons\cms\model\Channel;
use addons\cms\model\Modelx;
use app\common\controller\Frontend;
use app\common\model\User;
use fast\Tree;
use think\Db;
use think\Exception;
use think\Validate;

/**
 * 会员文档
 */
class Archives extends Frontend
{
    protected $layout = 'default';
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    /**
     * 发表文章
     */
    public function post()
    {
        $config = get_addon_config('cms');
        $id = $this->request->get('id');
        $archives = $id ? \app\admin\model\cms\Archives::get($id) : null;

        if ($archives) {
            $channel = Channel::get($archives['channel_id']);
            if (!$channel) {
                $this->error(__('未找到指定栏目'));
            }
            $model = \addons\cms\model\Modelx::get($channel['model_id']);
            if (!$model) {
                $this->error(__('未找到指定模型'));
            }
            if ($archives['user_id'] != $this->auth->id) {
                $this->error("无法进行越权操作！");
            }
        } else {
            $model = null;
            $model_id = $this->request->request('model_id');
            // 如果有model_id则调用指定模型
            if ($model_id) {
                $model = Modelx::get($model_id);
            }
        }

        // 如果来源于提交
        if ($this->request->isPost()) {
            if ($this->auth->score < $config['limitscore']['postarchives']) {
                $this->error("积分必须大于{$config['limitscore']['postarchives']}才可以发布文章");
            }

            $row = $this->request->post('row/a');
            $origin = $this->request->post('row/a', [], 'trim');
            $origin['content'] = isset($origin['content']) ? $origin['content'] : '';
            $row['content'] = $origin['content'];
            $token = $this->request->post('__token__');
            $rule = [
                'title|标题'      => 'require|length:3,100',
                'channel_id|栏目' => 'require|integer',
                '__token__'     => 'require|token'
            ];

            $msg = [
                'title.require'   => '标题不能为空',
                'title.length'    => '标题长度限制在3~100个字符',
                'channel_id'      => '栏目不能为空',
                'content.require' => '内容不能为空',
            ];
            $row['__token__'] = $token;
            $validate = new Validate($rule, $msg);
            $result = $validate->check($row);
            if (!$result) {
                $this->error($validate->getError(), null, ['token' => $this->request->token()]);
            }
            //审核状态
            $status = 'normal';
            if ($config['isarchivesaudit'] == 1) {
                $status = 'hidden';
            } elseif ($config['isarchivesaudit'] == 0) {
                $status = 'normal';
            } elseif (!Service::isContentLegal(implode('-', $row))) {
                $status = 'hidden';
            }

            $row['user_id'] = $this->auth->id;
            $row['status'] = $status;
            if ($status == 'normal') {
                $row['publishtime'] = time();
            }
            Db::startTrans();
            try {
                if ($archives) {
                    $archives->allowField(true)->save($row);
                } else {
                    (new \app\admin\model\cms\Archives)->allowField(true)->save($row);
                }
                //增加积分
                $status == 'normal' && User::score($config['score']['postarchives'], $this->auth->id, '发布文章');
                Db::commit();
            } catch (Exception $e) {
                Db::rollback();
                $this->error("发生错误:" . $e->getMessage());
            }
            if ($status === 'hidden') {
                //发送通知
                $status === 'hidden' && Service::notice('CMS收到一篇新的文章审核', $config['auditnotice'], $config['noticetemplateid']);
                $this->success("发布成功！请等待审核!");
            } else {
                $this->success("发布成功！");
            }
            exit;
        }

        $channel = new Channel();

        // 合并主副表
        if ($archives) {
            $addon = db($model['table'])->where('id', $archives['id'])->find();
            if ($addon) {
                $archives = array_merge($archives->toArray(), $addon);
            }
        }

        // 读取可发布的栏目列表
        $disabledIds = [];
        $channelList = collection(
            $channel->where('type', '<>', 'link')
                ->where("((type='list' AND iscontribute='1') OR type='channel')")
                ->order("weigh desc,id desc")
                ->cache(false)
                ->select()
        )->toArray();
        $channelParents = [];
        foreach ($channelList as $index => $item) {
            if ($item['parent_id']) {
                $channelParents[] = $item['parent_id'];
            }
        }
        foreach ($channelList as $k => $v) {
            if ($v['type'] != 'list' || ($model && $model['id'] != $v['model_id'])) {
                $disabledIds[] = $v['id'];
            }
            if ($v['type'] == 'channel' && !in_array($v['id'], $channelParents)) {
                unset($channelList[$k]);
            }
        }
        $tree = Tree::instance()->init($channelList, 'parent_id');
        $channelOptions = $tree->getTree(0, "<option model='@model_id' value=@id @selected @disabled>@spacer@name</option>", $archives ? $archives['channel_id'] : '', $disabledIds);
        $this->view->assign('channelOptions', $channelOptions);
        $this->view->assign([
            'archives'       => $archives,
            'channelOptions' => $channelOptions,
            'categoryList'   => ''
        ]);
        $this->assignconfig('archives_id', $archives ? $archives['id'] : 0);

        $modelName = $model ? $model['name'] : '文章';
        $this->view->assign('title', $archives ? "修改{$modelName}" : "发布{$modelName}");
        $this->view->assign('model', $model);
        return $this->view->fetch();
    }

    /**
     * 我的发布
     */
    public function my()
    {
        $archives = new \addons\cms\model\Archives;
        $model = null;
        $model_id = (int)$this->request->request('model_id');
        $channel_id = (int)$this->request->request('channel_id');
        // 如果有model_id则调用指定模型
        if ($model_id) {
            $model = Modelx::get($model_id);
            if ($model) {
                $archives->where('model_id', $model_id);
            }
        }
        $config = ['query' => []];
        if ($model) {
            $config['query']['model_id'] = $model_id;
        }
        if ($channel_id) {
            $config['query']['channel_id'] = $channel_id;
        }
        $user_id = $this->auth->id;
        $archivesList = $archives->where('user_id', $user_id)
            ->where($config['query'])
            ->order('id', 'desc')
            ->paginate(10, null, $config);

        $channelList = Channel::where('id', 'in', function ($query) use ($user_id) {
            $query->name('cms_archives')->where('user_id', $user_id)->field('channel_id');
        })->where('status', 'normal')->select();
        $this->view->assign('archivesList', $archivesList);
        $this->view->assign('channelList', $channelList);
        $this->view->assign('title', '我发布的' . ($model ? $model['name'] : '文章'));
        $this->view->assign('model', $model);
        return $this->view->fetch();
    }

    /**
     * 删除文档
     */
    public function delete()
    {
        $id = (int)$this->request->request('id/d');
        if (!$id) {
            $this->error("参数不正确");
        }
        $archives = \addons\cms\model\Archives::where('id', $id)->where('user_id', $this->auth->id)->find();
        if (!$archives) {
            $this->error("未找到指定的文档");
        }
        Db::startTrans();
        try {
            $archives->delete();
            $archives->channel->setDec("items");
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error("删除文档失败");
        }
        $this->success("删除文档成功");
    }

    /**
     * 获取栏目列表
     * @internal
     */
    public function get_channel_fields()
    {
        $this->view->engine->layout(false);
        $channel_id = $this->request->post('channel_id');
        $archives_id = $this->request->post('archives_id');
        $channel = Channel::get($channel_id, 'model');
        if ($channel && $channel['type'] === 'list') {
            $values = [];
            if ($archives_id) {
                $values = db($channel['model']['table'])->where('id', $archives_id)->find();
            }

            $fields = \app\admin\model\cms\Fields::where('model_id', $channel['model_id'])
                ->where('iscontribute', 1)
                ->where('status', 'normal')
                ->order('weigh desc,id desc')
                ->select();
            foreach ($fields as $k => $v) {
                //优先取编辑的值,再次取默认值
                $v->value = isset($values[$v['name']]) ? $values[$v['name']] : (is_null($v['defaultvalue']) ? '' : $v['defaultvalue']);
                $v->rule = str_replace(',', '; ', $v->rule);
                if (in_array($v['type'], ['checkbox', 'lists', 'images'])) {
                    $checked = '';
                    if ($v['minimum'] && $v['maximum']) {
                        $checked = "{$v['minimum']}~{$v['maximum']}";
                    } elseif ($v['minimum']) {
                        $checked = "{$v['minimum']}~";
                    } elseif ($v['maximum']) {
                        $checked = "~{$v['maximum']}";
                    }
                    if ($checked) {
                        $v->rule .= (';checked(' . $checked . ')');
                    }
                }

                if (in_array($v['type'], ['checkbox', 'radio']) && stripos($v->rule, 'required') !== false) {
                    $v->rule = str_replace('required', 'checked', $v->rule);
                }
                if (in_array($v['type'], ['selects'])) {
                    $v->extend .= (' ' . 'data-max-options="' . $v['maximum'] . '"');
                }
            }

            $this->view->assign('fields', $fields);
            $this->view->assign('values', $values);
            $this->success('', null, ['html' => $this->view->fetch('fields')]);
        } else {
            $this->error(__('请选择栏目'));
        }
        $this->error(__('参数不能为空', 'ids'));
    }

    /**
     * 标签自动完成
     * @internal
     */
    public function tags_autocomplete()
    {
        $q = $this->request->request('q');
        $list = \addons\cms\model\Tags::where('name', 'like', '%' . $q . '%')->limit(10)->column('name');
        echo json_encode($list);
        return;
    }
}
