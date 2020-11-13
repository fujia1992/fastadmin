<?php

return array(

    array(
        'name'    => 'system_user_id',
        'title'   => '平台会员ID',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '1',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '用于统计平台收入的前台会员ID',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'sitename',
        'title'   => '站点名称',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '我的CMS网站',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'sitelogo',
        'title'   => '站点Logo',
        'type'    => 'image',
        'content' =>
            array(),
        'value'   => '/assets/addons/cms/img/logo.png',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'title',
        'title'   => '首页标题',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'keywords',
        'title'   => '首页关键字',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'description',
        'title'   => '首页描述',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'theme',
        'title'   => '皮肤',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => 'default',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '请确保addons/cms/view有相应的目录',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'qrcode',
        'title'   => '公众号二维码',
        'type'    => 'image',
        'content' =>
            array(),
        'value'   => '/assets/addons/cms/img/qrcode.png',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'wxapp',
        'title'   => '小程序二维码',
        'type'    => 'image',
        'content' =>
            array(),
        'value'   => '/assets/addons/cms/img/qrcode.png',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'donateimage',
        'title'   => '打赏图片',
        'type'    => 'image',
        'content' =>
            array(),
        'value'   => '/assets/addons/cms/img/qrcode.png',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '打赏图片，请使用300*300的图片',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'default_archives_img',
        'title'   => '文档默认图片',
        'type'    => 'image',
        'content' =>
            array(),
        'value'   => '/assets/addons/cms/img/noimage.jpg',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'default_channel_img',
        'title'   => '栏目默认图片',
        'type'    => 'image',
        'content' =>
            array(),
        'value'   => '/assets/addons/cms/img/noimage.jpg',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'default_block_img',
        'title'   => '区块默认图片',
        'type'    => 'image',
        'content' =>
            array(),
        'value'   => '/assets/addons/cms/img/noimage.jpg',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'default_page_img',
        'title'   => '单页默认图片',
        'type'    => 'image',
        'content' =>
            array(),
        'value'   => '/assets/addons/cms/img/noimage.jpg',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'default_special_img',
        'title'   => '专题默认图片',
        'type'    => 'image',
        'content' =>
            array(),
        'value'   => '/assets/addons/cms/img/noimage.jpg',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'downloadtype',
        'title'   => '下载类型字典',
        'type'    => 'array',
        'content' =>
            array(),
        'value'   =>
            array(
                'baidu' => '百度网盘',
                'local' => '本地',
                'other' => '其它',
            ),
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'archivesratio',
        'title'   => '付费文章分成',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '1:0',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '平台:文章作者 <br>请保证两者相加为1',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'score',
        'title'   => '获取积分设置',
        'type'    => 'array',
        'content' =>
            array(),
        'value'   =>
            array(
                'postarchives' => 0,
                'postcomment'  => 0,
            ),
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '如果问题或评论被删除则会扣除相应的积分',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'limitscore',
        'title'   => '限定积分设置',
        'type'    => 'array',
        'content' =>
            array(),
        'value'   =>
            array(
                'postarchives' => 0,
                'postcomment'  => 0,
            ),
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '必须达到相应的积分限制条件才可以操作',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'userpage',
        'title'   => '会员个人主页',
        'type'    => 'radio',
        'content' =>
            array(
                1 => '开启',
                0 => '关闭',
            ),
        'value'   => '1',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '是否开启会员个人主页功能',
        'extend'  => '',
    ),

    array(
        'name'    => 'domain',
        'title'   => '绑定二级域名前缀',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'rewrite',
        'title'   => '伪静态',
        'type'    => 'array',
        'content' =>
            array(),
        'value'   =>
            array(
                'index/index'    => '/cms/$',
                'tags/index'     => '/cms/t/[:name]$',
                'page/index'     => '/cms/p/[:diyname]$',
                'search/index'   => '/cms/s$',
                'diyform/index'  => '/cms/d/[:diyname]',
                'special/index'  => '/cms/special/[:diyname]',
                'archives/index' => '/cms/a/[:diyname]$',
                'channel/index'  => '/cms/c/[:diyname]$',
                'user/index'     => '/u/[:id]',
            ),
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'wxappid',
        'title'   => '小程序AppID',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'wxappsecret',
        'title'   => '小程序AppSecret',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'ispaylogin',
        'title'   => '支付是否需要登录',
        'type'    => 'radio',
        'content' =>
            array(
                1 => '是',
                0 => '否',
            ),
        'value'   => '1',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '支付时是否需要登录',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'paytypelist',
        'title'   => '支付模块',
        'type'    => 'checkbox',
        'content' =>
            array(
                'wechat'  => '微信支付',
                'alipay'  => '支付宝',
                'balance' => '余额支付',
            ),
        'value'   => 'wechat,alipay,balance',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '前台支付开启的模块',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'defaultpaytype',
        'title'   => '默认支付模块',
        'type'    => 'radio',
        'content' =>
            array(
                'wechat'  => '微信支付',
                'alipay'  => '支付宝',
                'balance' => '余额支付',
            ),
        'value'   => 'balance',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '前台内容页默认支付模块',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'isarchivesaudit',
        'title'   => '发布文章审核',
        'type'    => 'radio',
        'content' =>
            array(
                1  => '全部审核',
                0  => '无需审核',
                -1 => '仅含有过滤词时审核',
            ),
        'value'   => '1',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'iscommentaudit',
        'title'   => '发表评论审核',
        'type'    => 'radio',
        'content' =>
            array(
                1  => '全部审核',
                0  => '无需审核',
                -1 => '仅含有过滤词时审核',
            ),
        'value'   => '-1',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'audittype',
        'title'   => '审核方式',
        'type'    => 'radio',
        'content' =>
            array(
                'local'    => '本地',
                'baiduyun' => '百度云',
            ),
        'value'   => 'local',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '如果启用百度云，请输入百度云AI平台应用的AK和SK',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'nlptype',
        'title'   => '分词方式',
        'type'    => 'radio',
        'content' =>
            array(
                'local'    => '本地',
                'baiduyun' => '百度云',
            ),
        'value'   => 'local',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '如果启用百度云，请输入百度云AI平台应用的AK和SK',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'aip_appid',
        'title'   => '百度AI平台应用Appid',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '百度云AI开放平台应用AppId',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'aip_apikey',
        'title'   => '百度AI平台应用Apikey',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '百度云AI开放平台应用ApiKey',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'aip_secretkey',
        'title'   => '百度AI平台应用Secretkey',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '百度云AI开放平台应用Secretkey',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'apikey',
        'title'   => 'ApiKey',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '用于调用API接口时写入数据权限控制<br>可以为空',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'archiveseditmode',
        'title'   => '文档编辑模式',
        'type'    => 'radio',
        'content' =>
            array(
                'addtabs' => '新选项卡',
                'dialog'  => '弹窗',
            ),
        'value'   => 'dialog',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '在添加或编辑文档时的操作方式',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'auditnotice',
        'title'   => '审核通知',
        'type'    => 'radio',
        'content' =>
            array(
                'none'     => '无需通知',
                'dinghorn' => '钉钉小喇叭',
                'vbot'     => '企业微信通知',
            ),
        'value'   => 'none',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '如需启用审核通知，务必在插件市场安装对应的插件',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'noticetemplateid',
        'title'   => '消息模板ID',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '1',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '当启用审核通知时，消息通知的模板ID',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'channelallocate',
        'title'   => '栏目授权',
        'type'    => 'radio',
        'content' =>
            array(
                1 => '开启',
                0 => '关闭',
            ),
        'value'   => '0',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '开启后可以单独给管理员分配可管理的内容栏目',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'archivesdatalimit',
        'title'   => '文章数据范围',
        'type'    => 'select',
        'content' =>
            array(
                'all'      => '可查看全部数据',
                'auth'     => '仅可查看自己和子级发布的数据',
                'personal' => '仅可查看自己发布的数据',
            ),
        'value'   => 'all',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'specialdatalimit',
        'title'   => '专题数据范围',
        'type'    => 'select',
        'content' =>
            array(
                'all'      => '可查看全部数据',
                'auth'     => '仅可查看自己和子级发布的数据',
                'personal' => '仅可查看自己发布的数据',
            ),
        'value'   => 'all',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'pagedatalimit',
        'title'   => '单页数据范围',
        'type'    => 'select',
        'content' =>
            array(
                'all'      => '可查看全部数据',
                'auth'     => '仅可查看自己和子级发布的数据',
                'personal' => '仅可查看自己发布的数据',
            ),
        'value'   => 'all',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'diyformdatalimit',
        'title'   => '自定义表单数据范围',
        'type'    => 'select',
        'content' =>
            array(
                'all'      => '可查看全部数据',
                'auth'     => '仅可查看自己和子级发布的数据',
                'personal' => '仅可查看自己发布的数据',
            ),
        'value'   => 'all',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'contactqq',
        'title'   => '联系我们QQ',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '合作伙伴和友情链接的联系QQ',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'autolinks',
        'title'   => '关键字链接',
        'type'    => 'array',
        'content' =>
            array(),
        'value'   =>
            array(
                '服务器' => 'https://www.fastadmin.net/go/aliyun',
                '阿里云' => 'https://www.fastadmin.net/go/aliyun',
            ),
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '对应的关键字将会自动加上链接',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'searchtype',
        'title'   => '搜索方式',
        'type'    => 'radio',
        'content' =>
            array(
                'local'     => '本地搜索，采用Like(无需配置,效率低)',
                'xunsearch' => '采用Xunsearch全文搜索(需安装插件+配置)',
            ),
        'value'   => 'local',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '如果启用Xunsearch全文搜索，需安装Xunsearch插件并配置Xunsearch服务端',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'autopinyin',
        'title'   => '标题自动转拼音',
        'type'    => 'radio',
        'content' =>
            array(
                1 => '开启',
                0 => '关闭',
            ),
        'value'   => '1',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '如果开启自动转拼音，则在录入文档标题或栏目名称时，自定义名称将自动转换成拼音',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'baidupush',
        'title'   => '百度主动推送链接',
        'type'    => 'radio',
        'content' =>
            array(
                1 => '开启',
                0 => '关闭',
            ),
        'value'   => '0',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '如果开启百度主动推送链接，将在文章发布时自动进行推送',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'usersidenav',
        'title'   => '会员中心边栏模块',
        'type'    => 'checkbox',
        'content' =>
            array(
                'myarchives'   => '我发布的文章',
                'postarchives' => '发布文章',
                'myorder'      => '我的消费订单',
                'mycomment'    => '我发表的评论',
            ),
        'value'   => 'myarchives,postarchives,myorder,mycomment',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '会员中心边栏模块',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'loadmode',
        'title'   => '列表页加载模式',
        'type'    => 'radio',
        'content' =>
            array(
                'infinite' => '无限加载模式',
                'paging'   => '分页加载模式',
            ),
        'value'   => 'paging',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '列表页加载模式',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'pagemode',
        'title'   => '分页加载模式',
        'type'    => 'radio',
        'content' =>
            array(
                'simple' => '仅使用上下页',
                'full'   => '包含数字分页',
            ),
        'value'   => 'simple',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '分页加载模式',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'cachelifetime',
        'title'   => '缓存默认时长',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => 'true',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '0表示不缓存,具体数字表示缓存时长,true表示永久缓存',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'flagtype',
        'title'   => '标志字典',
        'type'    => 'array',
        'content' =>
            array(),
        'value'   =>
            array(
                'hot'       => '热门',
                'new'       => '新',
                'recommend' => '推荐',
                'top'       => '置顶',
            ),
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => 'urlsuffix',
        'title'   => 'URL后缀',
        'type'    => 'string',
        'content' =>
            array(
                1 => '开启',
                0 => '关闭',
            ),
        'value'   => 'html',
        'rule'    => '',
        'msg'     => '如果不需要后缀可以设置为空',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),

    array(
        'name'    => '__tips__',
        'title'   => '温馨提示',
        'type'    => 'string',
        'content' =>
            array(),
        'value'   => '1.如果需要将CMS绑定到首页,请移除伪静态中的<b>cms/</b><br>
                      2.默认CMS不包含富文本编辑器插件，请在<a href="https://www.fastadmin.net/store.html?category=16" target="_blank">插件市场</a>按需要安装<br>
                      3.如果需要启用付费阅读或付费下载,请务必安装<a href="https://www.fastadmin.net/store/epay.html" target="_blank">微信支付宝</a>整合插件<br>
                      4.如需启用审核通知，请在插件市场安装<a href="https://www.fastadmin.net/store/dinghorn.html" target="_blank">钉钉</a>或<a href="https://www.fastadmin.net/store/vbot.html" target="_blank">微信</a>通知插件',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
);
