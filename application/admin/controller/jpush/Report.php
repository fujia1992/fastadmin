<?php

namespace app\admin\controller\jpush;

use app\common\controller\Backend;
use addons\jpush\library\jpush\Client;
use addons\jpush\library\jpush\Exceptions\JPushException;

/**
 * 极光推送管理
 *
 * @icon fa fa-list-alt
 */
class Report extends Backend
{
    private $appKey = '';
    private $masterSecret = '';
    /**
     * @var Client
     */
    private $client = null;

    public function _initialize()
    {
        parent::_initialize();
        $config = get_addon_config('jpush');
        $this->appKey = $config['AppKey'];
        $this->masterSecret = $config['MasterSecret'];
        $this->client = new Client($this->appKey, $this->masterSecret);
        $this->model = model('JpushLog');
    }

    /**
     * 查看发送成功记录
     */
    public function received()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 获取送达统计信息
     */
    public function recDetail($ids)
    {
        $row = $this->model->get(['id' => $ids]);
        if (!$row)
            $this->error(__('No Results were found'));

        $recData = $this->client->report()->getReceived([(string)$row->msg_id]);
        if (isset($recData['http_code']) && $recData['http_code'] == 200) {
            $data = $recData['body'][0];
            $row['android_received']  = $data['android_received'] ?? 0;
            $row['ios_apns_received'] = $data['ios_apns_received'] ?? 0;
            $row['ios_apns_sent']     = $data['ios_apns_sent'] ?? 0;
            $row['ios_msg_received']  = $data['ios_msg_received'] ?? 0;
            $row['wp_mpns_sent']      = $data['wp_mpns_sent'] ?? 0;
        }
        unset($row->id);

        $this->view->assign("row", $row->toArray());
        return $this->view->fetch();
    }

    /**
     * 获取送达统计信息
     */
    public function messages()
    {
        if ($this->request->isAjax()) {
            $msgId = $this->request->post('msg_id');
            $rid = explode(',', $this->request->post('rid'));
            $date = $this->request->post('date');
            if ($msgId && $rid) {
                try{
                    $res = $this->client->report()->getMessageStatus((int)$msgId, $rid, $date);
                    if (isset($res['http_code']) && $res['http_code'] == 200) {
                        $this->success('', null, $res);
                    }
                } catch (JPushException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty'));
        }
        return $this->view->fetch();
    }
}