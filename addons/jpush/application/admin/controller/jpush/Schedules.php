<?php

namespace app\admin\controller\jpush;

use app\common\controller\Backend;
use addons\jpush\library\jpush\Client;
use addons\jpush\library\jpush\Exceptions\APIRequestException;

/**
 * 极光推送管理
 *
 * @icon fa fa-list-alt
 */
class Schedules extends Backend
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
     * 获取定时任务
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $offset = $this->request->get('offset', 0);
            $limit = $this->request->get('limit', 20);
            $page = $offset / $limit + 1;
            try {
                $res = $this->client->schedule()->getSchedules($page);
                if (isset($res['http_code']) && $res['http_code'] == 200) {
                    $data = ["total" => $res['body']['total_count'], "rows" => $res['body']['schedules']];
                    return json($data);
                }
            } catch (APIRequestException $e) {
                $this->error($e->getMessage());
            }
        }
        return $this->view->fetch();
    }

    /**
     * 删除定时任务
     */
    public function del($ids = "")
    {
        if ($ids) {
            $res = $this->client->schedule()->deleteSchedule($ids);
            if (isset($res['http_code']) && $res['http_code'] == 200) {
                $this->success();
            }
            $this->error(__('No rows were deleted'));
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }
}