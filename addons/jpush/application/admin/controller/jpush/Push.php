<?php

namespace app\admin\controller\jpush;

use app\common\controller\Backend;
use addons\jpush\library\jpush\Client;
use addons\jpush\library\jpush\Exceptions\JPushException;
use think\Db;

/**
 * 极光推送管理
 *
 * @icon fa fa-list-alt
 */
class Push extends Backend
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
    }

    /**
     * 发送通知
     */
    public function notification()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isPost()) {
            $pusher = $this->client->push();

            $content = $this->request->post('content', '');
            $platform = $this->request->post('platform/a');
            $crowd = $this->request->post('crowd/a');
            $when = $this->request->post('when', 'now');
            $wcs = $this->request->post('wcs');
            if (!$content) {
                $this->error(__('Content is empty'));
            }
            if (!$platform[0]) {
                $this->error(__('Platform is empty'));
            }
            if (!$crowd) {
                $this->error(__('Crowd is empty'));
            }

            $pusher->setNotificationAlert($content);
            $pusher->setPlatform($platform);
            $receiver = [];
            foreach ($crowd as $k => $v) {
                switch ($k) {
                    case 'all':
                        $receiver[] = 'All';
                        $pusher->addAllAudience();
                        break;
                    case 'tag':
                        $receiver[] = __('Tag');
                        $tag = $this->request->post('tag/a');
                        if (!$tag) {
                            $this->error(__('Tag is empty'));
                        }
                        foreach ($tag as $x => $y) {
                            $value = $this->request->post($x);
                            if ($value) {
                                $value = explode(',', $value);
                                switch ($x) {
                                    case 'union':
                                        $pusher->addTag($value);
                                        break;
                                    case 'intersection':
                                        $pusher->addTagAnd($value);
                                        break;
                                    case 'complement':
                                        $pusher->addTagNot($value);
                                }
                            }
                        }
                        break;
                    case 'alias':
                        $receiver[] = __('Alias');
                        $alias = $this->request->post('alias');
                        if ($alias) {
                            $pusher->addAlias($alias);
                        }
                        break;
                    case 'rid':
                        $receiver[] = 'reg.ID';
                        $rid = $this->request->post('rid');
                        if ($rid) {
                            $pusher->addRegistrationId($rid);
                        }
                }
            }
            if ($wcs) {
                $cs = $this->request->post('cs', 1);
                $pusher->options([
                    'big_push_duration' => $cs
                ]);
            }
            try {
                if ($when === 'timing') {
                    $timing = $this->request->post('timing');
                    if (!$timing) {
                        $this->error(__('Timing is empty'));
                    }
                    $payload = $pusher->build();
                    $res = $this->client->schedule()->createSingleSchedule('Crontab' . date('YmdHis'), $payload, ['time' => $timing]);
                } else {
                    $res = $pusher->send();
                }
                if (isset($res['http_code']) && $res['http_code'] == 200) {
                    if (isset($res['body']['sendno']) && isset($res['body']['msg_id'])) {
                        model('JpushLog')->save([
                            'sendno' => $res['body']['sendno'],
                            'msg_id' => $res['body']['msg_id'],
                            'push_type' => $when,
                            'receiver' => implode(',', $receiver),
                            'content' => $content,
                            'platform' => implode(',', $platform)
                        ]);
                    }
                    $this->success(__('Push success'));
                }
                $this->error(__('Push failed'));
            } catch (JPushException $e) {
                $this->error(__('Push failed') . ': ' . $e->getMessage());
            }
        }
        return $this->view->fetch();
    }
}