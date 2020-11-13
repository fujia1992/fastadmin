<?php

namespace addons\cms\controller\wxapp;

use addons\cms\model\Archives;
use addons\cms\model\Block;
use addons\cms\model\Channel;

/**
 * 首页
 */
class Index extends Base
{
    protected $noNeedLogin = '*';

    /**
     * 首页
     */
    public function index()
    {
        $bannerList = [];
        $list = Block::getBlockList(['name' => 'indexfocus', 'row' => 5]);
        foreach ($list as $index => $item) {
            $bannerList[] = ['image' => cdnurl($item['image'], true), 'url' => '/', 'title' => $item['title']];
        }

        $tabList = [
            ['id' => 0, 'title' => '全部'],
        ];
        $channelList = Channel::where('status', 'normal')
            ->where('type', 'in', ['list'])
            ->field('id,parent_id,name,diyname')
            ->order('weigh desc,id desc')
            ->cache(false)
            ->select();
        foreach ($channelList as $index => $item) {
            $tabList[] = ['id' => $item['id'], 'title' => $item['name']];
        }
        $archivesList = Archives::getArchivesList(['cache' => false]);
        $archivesList = collection($archivesList)->toArray();
        foreach ($archivesList as $index => &$item) {
            $item['url'] = $item['fullurl'];
            //小程序只显示3张图
            $item['images_list'] = array_slice(array_filter(explode(',', $item['images'])), 0, 3);
            unset($item['imglink'], $item['textlink'], $item['channellink'], $item['tagslist'], $item['weigh'], $item['status'], $item['deletetime'], $item['memo'], $item['img']);
        }
        $data = [
            'bannerList'   => $bannerList,
            'tabList'      => $tabList,
            'archivesList' => $archivesList,
        ];
        $this->success('', $data);
    }
}
