<?php

namespace app\admin\controller\jpush;

use app\common\controller\Backend;
use addons\jpush\library\jpush\Client;
use addons\jpush\library\jpush\Exceptions\APIRequestException;
use InvalidArgumentException;

/**
 * 极光推送管理
 *
 * @icon fa fa-list-alt
 */
class Devices extends Backend
{

    private $appKey = '';
    private $masterSecret = '';
    /**
     * @var Client
     */
    public $client = null;

    public function _initialize()
    {
        parent::_initialize();
        $config = get_addon_config('jpush');
        $this->appKey = $config['AppKey'];
        $this->masterSecret = $config['MasterSecret'];
        $this->client = new Client($this->appKey, $this->masterSecret);
    }

    /**
     * 获取设备属性
     */
    public function get_all_attr()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $rid = $this->request->post('rid');
            try {
                $res = $this->client->device()->getDevices($rid);
                if (isset($res['http_code']) && $res['http_code'] == 200) {
                    $this->success('', null, $res['body']);
                }
            } catch (APIRequestException $e) {
                $this->error($e->getMessage());
            }
        }
        return $this->view->fetch();
    }

    /**
     * 设置设备属性
     */
    public function set_all_attr()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $rid = $this->request->post('rid');
            $addTags = $this->request->post('add_tags');
            $delTags = $this->request->post('del_tags', null);
            $alias = $this->request->post('alias');
            $mobile = $this->request->post('mobile');
            try {
                $addTags = $addTags ? explode(',', $addTags) : null;
                $delTags = $delTags ? explode(',', $delTags) : null;
                $res = $this->client->device()->updateDevice($rid, $alias, $mobile, $addTags, $delTags);
                if (isset($res['http_code']) && $res['http_code'] == 200) {
                    $this->success('', null, $res['body']);
                }
            } catch (APIRequestException $e) {
                $this->error($e->getMessage());
            }
        }
        return $this->view->fetch();
    }

    /**
     * 获取别名对应设备
     */
    public function get_rid_by_alias()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $alias = $this->request->post('alias');
            $platform = $this->request->post('platform') ? $this->request->post('platform') : null;
            try {
                $res = $this->client->device()->getAliasDevices($alias, $platform);
                if (isset($res['http_code']) && $res['http_code'] == 200) {
                    $this->success('', null, $res['body']);
                }
            } catch (APIRequestException $e) {
                $this->error($e->getMessage());
            }
        }
        return $this->view->fetch();
    }

    /**
     * 删除别名
     */
    public function del_alias()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $alias = $this->request->post('alias');
            try {
                $res = $this->client->device()->deleteAlias($alias);
                if (isset($res['http_code']) && $res['http_code'] == 200) {
                    $this->success();
                }
            } catch (APIRequestException $e) {
                $this->error($e->getMessage());
            }
        }
        return $this->view->fetch();
    }

    /**
     * 获取所有标签
     */
    public function get_tags()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            try {
                $res = $this->client->device()->getTags();
                if (isset($res['http_code']) && $res['http_code'] == 200) {
                    $data = [];
                    foreach ($res['body']['tags'] as $k => $v) {
                        $data[]['id'] = $v;
                    }
                    return json(["total" => count($data), "rows" => $data]);
                }
            } catch (APIRequestException $e) {
                $this->error($e->getMessage());
            }
        }
        return $this->view->fetch();
    }

    /**
     * 更新标签
     */
    public function edit_tag($ids)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isPost()) {
            try {
                $tag = $this->request->post('tag');
                $addRid = $this->request->post('add_rid');
                $delRid = $this->request->post('del_rid');
                $addRid = $addRid ? explode(',', $addRid) : null;
                $delRid = $delRid ? explode(',', $delRid) : null;
                $res = $this->client->device()->updateTag($tag, $addRid, $delRid);
                if (isset($res['http_code']) && $res['http_code'] == 200) {
                    $this->success();
                }
            } catch (InvalidArgumentException $e) {
                $this->error($e->getMessage());
            } catch (APIRequestException $e) {
                $this->error($e->getMessage());
            }
        }
        $this->assign('tag', $ids);
        return $this->view->fetch();
    }

    /**
     * 删除标签
     */
    public function del_tag($ids)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            try {
                $res = $this->client->device()->deleteTag($ids);
                if (isset($res['http_code']) && $res['http_code'] == 200) {
                    $this->success();
                }
            } catch (APIRequestException $e) {
                $this->error($e->getMessage());
            }
        }
        return $this->view->fetch();
    }
}