
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- 表的结构 `__PREFIX__cms_addondownload`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_addondownload` (
  `id` int(10) NOT NULL,
  `content` longtext NOT NULL,
  `os` set('windows','linux','mac','ubuntu') DEFAULT '' COMMENT '操作系统',
  `version` varchar(255) DEFAULT '' COMMENT '最新版本',
  `filesize` varchar(255) DEFAULT '' COMMENT '文件大小',
  `language` set('zh-cn','en') DEFAULT '' COMMENT '语言',
  `downloadurl` varchar(1500) DEFAULT '' COMMENT '下载地址',
  `screenshots` varchar(1500) DEFAULT '' COMMENT '预览截图',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `downloads` varchar(10) DEFAULT '0' COMMENT '下载次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='下载';

--
-- 表的结构 `__PREFIX__cms_addonnews`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_addonnews` (
  `id` int(10) NOT NULL,
  `content` longtext NOT NULL,
  `author` varchar(50) DEFAULT '' COMMENT '作者',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新闻';

--
-- 表的结构 `__PREFIX__cms_addonproduct`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_addonproduct` (
  `id` int(10) NOT NULL,
  `content` longtext NOT NULL,
  `productdata` varchar(1500) DEFAULT '' COMMENT '产品列表',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品表';
COMMIT;

--
-- 表的结构 `__PREFIX__cms_archives`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_archives` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '会员ID',
  `channel_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `channel_ids` varchar(255) DEFAULT '' COMMENT '副栏目ID集合',
  `model_id` int(10) NOT NULL DEFAULT '0' COMMENT '模型ID',
  `special_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '专题ID集合',
  `admin_id` int(10) unsigned DEFAULT '0' COMMENT '管理员ID',
  `title` varchar(80) NOT NULL DEFAULT '' COMMENT '文章标题',
  `flag` varchar(100) DEFAULT '' COMMENT '标志',
  `style` varchar(100) NULL DEFAULT '' COMMENT '样式',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `images` varchar(1500) NOT NULL DEFAULT '' COMMENT '组图',
  `seotitle` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `tags` varchar(255) NOT NULL DEFAULT '' COMMENT 'TAG',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `views` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `comments` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论次数',
  `likes` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点赞数',
  `dislikes` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点踩数',
  `diyname` varchar(50) NOT NULL DEFAULT '' COMMENT '自定义URL',
  `isguest` tinyint(1) unsigned DEFAULT '1' COMMENT '是否访客访问',
  `iscomment` tinyint(1) unsigned DEFAULT '1' COMMENT '是否允许评论',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `publishtime` int(10) DEFAULT NULL COMMENT '发布时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `memo` varchar(100) DEFAULT '' COMMENT '备注',
  `status` enum('normal','hidden','rejected','pulloff') NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `status` (`channel_id`,`status`),
  KEY `channel` (`channel_id`,`weigh`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='内容表';

--
-- 表的结构 `__PREFIX__cms_block`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_block` (
  `id` smallint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '类型',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '图片',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '链接',
  `content` mediumtext COMMENT '内容',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `begintime` int(10) DEFAULT NULL COMMENT '开始时间',
  `endtime` int(10) DEFAULT NULL COMMENT '结束时间',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='区块表';

--
-- 表的结构 `__PREFIX__cms_channel`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_channel` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('channel','page','link','list') NOT NULL COMMENT '类型',
  `model_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '模型ID',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '图片',
  `flag` varchar(100) DEFAULT '' COMMENT '标志',
  `seotitle` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `diyname` varchar(30) NOT NULL DEFAULT '' COMMENT '自定义名称',
  `outlink` varchar(255) NOT NULL DEFAULT '' COMMENT '外部链接',
  `items` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文章数量',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `channeltpl` varchar(100) NOT NULL DEFAULT '' COMMENT '栏目页模板',
  `listtpl` varchar(100) NOT NULL DEFAULT '' COMMENT '列表页模板',
  `showtpl` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页模板',
  `pagesize` smallint(5) NOT NULL DEFAULT '0' COMMENT '分页大小',
  `iscontribute` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可投稿',
  `isnav` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否导航显示',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `diyname` (`diyname`),
  KEY `weigh` (`weigh`,`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='栏目表';

--
-- 表的结构 `__PREFIX__cms_channel_admin`
--

CREATE TABLE `__PREFIX__cms_channel_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `channel_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_id` (`admin_id`,`channel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='栏目权限表';

--
-- 表的结构 `__PREFIX__cms_comment`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_comment` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '会员ID',
  `type` enum('archives','page', 'special') NOT NULL DEFAULT 'archives' COMMENT '类型',
  `aid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '关联ID',
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '父ID',
  `content` text COMMENT '内容',
  `comments` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论数',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'IP',
  `useragent` varchar(255) NOT NULL DEFAULT '' COMMENT 'User Agent',
  `subscribe` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '订阅',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `post_id` (`aid`,`pid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
-- 表的结构 `__PREFIX__cms_diyform`
-- ----------------------------

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_diyform`(
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned DEFAULT '0' COMMENT '管理员ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '表单名称',
  `title` varchar(100) DEFAULT NULL COMMENT '表单标题',
  `seotitle` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `keywords` varchar(100) DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `table` varchar(50) NOT NULL DEFAULT '' COMMENT '表名',
  `fields` text COMMENT '字段列表',
  `needlogin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要登录',
  `successtips` varchar(255) DEFAULT NULL COMMENT '成功提示文字',
  `redirecturl` varchar(100) DEFAULT NULL COMMENT '成功后跳转链接',
  `formtpl` varchar(30) NOT NULL DEFAULT '' COMMENT '表单页模板',
  `diyname` varchar(30) DEFAULT NULL COMMENT '自定义名称',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `setting` varchar(1500) DEFAULT NULL COMMENT '表单配置',
  `status` enum('normal','hidden') DEFAULT 'hidden' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='自定义表单表';

--
-- 表的结构 `__PREFIX__cms_fields`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_fields` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `model_id` int(10) NOT NULL DEFAULT '0' COMMENT '模型ID',
  `diyform_id` int(10) NOT NULL DEFAULT '0' COMMENT '表单ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '名称',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '类型',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text COMMENT '内容',
  `defaultvalue` varchar(100) NOT NULL DEFAULT '' COMMENT '默认值',
  `rule` varchar(100) DEFAULT '' COMMENT '验证规则',
  `msg` varchar(30) DEFAULT '0' COMMENT '错误消息',
  `ok` varchar(30) DEFAULT '0' COMMENT '成功消息',
  `tip` varchar(30) DEFAULT '' COMMENT '提示消息',
  `decimals` tinyint(1) DEFAULT NULL COMMENT '小数点',
  `length` mediumint(8) DEFAULT NULL COMMENT '长度',
  `minimum` smallint(6) DEFAULT NULL COMMENT '最小数量',
  `maximum` smallint(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最大数量',
  `extend` varchar(255) NOT NULL DEFAULT '' COMMENT '扩展信息',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `iscontribute` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可投稿',
  `isfilter` tinyint(1) NOT NULL DEFAULT '0' COMMENT '筛选',
  `status` enum('normal','hidden') NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`) USING BTREE,
  KEY `diyform_id` (`diyform_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='模型字段表';

-- ----------------------------
-- 表的结构 `__PREFIX__cms_message`
-- ----------------------------

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL COMMENT '会员ID',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `name` varchar(50) DEFAULT '' COMMENT '姓名',
  `telephone` varchar(100) DEFAULT '' COMMENT '电话',
  `qq` varchar(30) DEFAULT '' COMMENT 'QQ',
  `content` text COMMENT '内容',
  `image` varchar(100) DEFAULT '' COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='站内留言';

--
-- 表的结构 `__PREFIX__cms_model`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_model` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `table` char(20) NOT NULL DEFAULT '' COMMENT '表名',
  `fields` text COMMENT '字段列表',
  `channeltpl` varchar(30) NOT NULL DEFAULT '' COMMENT '栏目页模板',
  `listtpl` varchar(30) NOT NULL DEFAULT '' COMMENT '列表页模板',
  `showtpl` varchar(30) NOT NULL DEFAULT '' COMMENT '详情页模板',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `setting` text COMMENT '模型配置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='内容模型表';

--
-- 表的结构 `__PREFIX__cms_order`
--

CREATE TABLE `__PREFIX__cms_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orderid` varchar(50) DEFAULT '' COMMENT '订单ID',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `archives_id` int(10) unsigned DEFAULT '0' COMMENT '文档ID',
  `title` varchar(100) DEFAULT NULL COMMENT '订单标题',
  `amount` double(10,2) unsigned DEFAULT '0.00' COMMENT '订单金额',
  `payamount` double(10,2) unsigned DEFAULT '0.00' COMMENT '支付金额',
  `paytype` varchar(50) DEFAULT NULL COMMENT '支付类型',
  `paytime` int(10) DEFAULT NULL COMMENT '支付时间',
  `ip` varchar(50) DEFAULT NULL COMMENT 'IP地址',
  `useragent` varchar(255) DEFAULT NULL COMMENT 'UserAgent',
  `memo` varchar(255) DEFAULT NULL COMMENT '备注',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` enum('created','paid','expired') DEFAULT 'created' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `archives_id` (`archives_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='订单表';

--
-- 表的结构 `__PREFIX__cms_page`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_page` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `category_id` int(10) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `admin_id` int(10) unsigned DEFAULT '0' COMMENT '管理员ID',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `seotitle` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `flag` varchar(100) DEFAULT '' COMMENT '标志',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `content` text COMMENT '内容',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `views` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点击',
  `likes` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点赞',
  `dislikes` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点踩',
  `comments` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论',
  `diyname` varchar(50) NOT NULL DEFAULT '' COMMENT '自定义',
  `showtpl` varchar(50) NOT NULL DEFAULT '' COMMENT '视图模板',
  `iscomment` tinyint(1) unsigned DEFAULT '1' COMMENT '是否允许评论',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='单页表';

--
-- 表的结构 `__PREFIX__cms_search_log`
--
CREATE TABLE `__PREFIX__cms_search_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keywords` varchar(100) CHARACTER SET utf8mb4 DEFAULT '' COMMENT '关键字',
  `nums` int(10) unsigned DEFAULT '0' COMMENT '搜索次数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keywords` (`keywords`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='搜索记录表';

--
-- 表的结构 `__PREFIX__cms_special`
--

CREATE TABLE `__PREFIX__cms_special` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned DEFAULT '0' COMMENT '管理员ID',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `tag_ids` varchar(1500) NULL DEFAULT '' COMMENT '标签ID集合',
  `andor` enum('and','or') NULL DEFAULT 'or' COMMENT '索引与或',
  `flag` varchar(100) DEFAULT '' COMMENT '标志',
  `label` varchar(50) NOT NULL DEFAULT '' COMMENT '标签',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '图片',
  `banner` varchar(100) NOT NULL DEFAULT '' COMMENT 'Banner图片',
  `diyname` varchar(50) NOT NULL DEFAULT '' COMMENT '自定义名称',
  `seotitle` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `keywords` varchar(100) DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `intro` varchar(255) DEFAULT NULL COMMENT '专题介绍',
  `views` int(10) unsigned DEFAULT '0' COMMENT '浏览次数',
  `comments` int(10) unsigned DEFAULT '0' COMMENT '评论次数',
  `iscomment` tinyint(1) unsigned DEFAULT '1' COMMENT '是否允许评论',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '专题模板',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='专题表';

--
-- 表的结构 `__PREFIX__cms_tags`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__cms_tags` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '标签名称',
  `archives` text COMMENT '文档ID集合',
  `nums` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档数量',
  `seotitle` varchar(100) DEFAULT '' COMMENT 'SEO标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `nums` (`nums`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='标签表';
COMMIT;

--
-- 转存表中的数据 `__PREFIX__cms_block`
--

INSERT INTO `__PREFIX__cms_block` (`id`, `type`, `name`, `title`, `image`, `url`, `content`, `weigh`, `createtime`, `updatetime`, `status`) VALUES
(1, '焦点图', 'indexfocus', '首页焦点图标题1', 'https://cdn.fastadmin.net/uploads/focus/25.jpg', 'http://www.fastadmin.net', '', 1, 1553606219, 1553606219, 'normal'),
(2, '焦点图', 'indexfocus', '首页焦点图标题2', 'https://cdn.fastadmin.net/uploads/focus/6.jpg', 'http://www.fastadmin.net', '', 2, 1553606219, 1553606219, 'normal'),
(3, '焦点图', 'indexfocus', '首页焦点图标题3', 'https://cdn.fastadmin.net/uploads/focus/24.jpg', 'http://www.fastadmin.net', '', 3, 1553606219, 1553606219, 'normal'),
(4, '文字', 'contactus', '联系我们', '', '', '', 4, 1553606219, 1553606219, 'normal'),
(5, '文字', 'partner', '合作伙伴', '', '', '<li><a href=\"/\"><img src=\"__ADDON__/img/logo/58.png\" /></a></li><li><a href=\"/\"><img src=\"__ADDON__/img/logo/360.png\" /></a></li><li><a href=\"/\"><img src=\"__ADDON__/img/logo/alipay.png\" /></a></li><li><a href=\"/\"><img src=\"__ADDON__/img/logo/baidu.png\" /></a></li><li><a href=\"/\"><img src=\"__ADDON__/img/logo/boc.png\" /></a></li><li><a href=\"/\"><img src=\"__ADDON__/img/logo/cctv.png\" /></a></li><li><a href=\"/\"><img src=\"__ADDON__/img/logo/didi.png\" /></a></li><li><a href=\"/\"><img src=\"__ADDON__/img/logo/iqiyi.png\" /></a></li><li><a href=\"/\"><img src=\"__ADDON__/img/logo/qq.png\" /></a></li><li><a href=\"/\"><img src=\"__ADDON__/img/logo/suning.png\" /></a></li><li><a href=\"/\"><img src=\"__ADDON__/img/logo/taobao.png\" /></a></li><li><a href=\"/\"><img src=\"__ADDON__/img/logo/tuniu.png\" /></a></li><li><a href=\"/\"><img src=\"__ADDON__/img/logo/weibo.png\" /></a></li>', 5, 1553606219, 1553757753, 'normal'),
(6, '文字', 'footer', '底部链接', '', '', '<div class=\"col-md-3 col-sm-3\">\n                            <div class=\"footer-logo\">\n                                <a href=\"#\"><i class=\"fa fa-bookmark\"></i></a>\n                            </div>\n                            <p class=\"copyright\"><small>© 2017-2020. All Rights Reserved. <br>\n                                    FastAdmin\n                                </small>\n                            </p>\n                        </div>\n                        <div class=\"col-md-5 col-md-push-1 col-sm-5 col-sm-push-1\">\n                            <div class=\"row\">\n                                <div class=\"col-xs-4\">\n                                    <ul class=\"links\">\n                                        <li><a href=\"#\">关于我们</a></li>\n                                        <li><a href=\"#\">发展历程</a></li>\n                                        <li><a href=\"#\">服务项目</a></li>\n                                        <li><a href=\"#\">团队成员</a></li>\n                                    </ul>\n                                </div>\n                                <div class=\"col-xs-4\">\n                                    <ul class=\"links\">\n                                        <li><a href=\"#\">新闻</a></li>\n                                        <li><a href=\"#\">资讯</a></li>\n                                        <li><a href=\"#\">推荐</a></li>\n                                        <li><a href=\"#\">博客</a></li>\n                                    </ul>\n                                </div>\n                                <div class=\"col-xs-4\">\n                                    <ul class=\"links\">\n                                        <li><a href=\"#\">服务</a></li>\n                                        <li><a href=\"#\">圈子</a></li>\n                                        <li><a href=\"#\">论坛</a></li>\n                                        <li><a href=\"#\">广告</a></li>\n                                    </ul>\n                                </div>\n                            </div>\n                        </div>\n                        <div class=\"col-md-3 col-sm-3 col-md-push-1 col-sm-push-1\">\n                            <div class=\"footer-social\">\n                                <a href=\"#\"><i class=\"fa fa-weibo\"></i></a>\n                                <a href=\"#\"><i class=\"fa fa-qq\"></i></a>\n                                <a href=\"#\"><i class=\"fa fa-wechat\"></i></a>\n                            </div>\n                        </div>', 6, 1553606219, 1553606219, 'normal'),
(7, '文字', 'friendlink', '友情链接 ', '', '', '<a href=\"https://www.fastadmin.net\" title=\"FastAdmin - 极速后台开发框架\">FastAdmin</a> <a href=\"https://gitee.com\" title=\"FastAdmin码云仓库\">码云</a> <a href=\"https://github.com\" title=\"FastAdminGithub仓库\">Github</a> <a href=\"https://doc.fastadmin.net\" title=\"FastAdmin文档 - 极速后台开发框架\">FastAdmin文档</a> <a href=\"https://ask.fastadmin.net\" title=\"FastAdmin问答社区 - 极速后台开发框架\">FastAdmin问答社区</a>', 7, 1553606219, 1553757863, 'normal'),
(8, '边栏', 'sidebarad1', '边栏广告1', 'https://cdn.fastadmin.net/assets/addons/ask/img/sidebar/howto.png', 'http://www.fastadmin.net', '<a href=\"https://www.fastadmin.net/store/ask.html\">\r\n    <img src=\"https://cdn.fastadmin.net/assets/addons/ask/img/sidebar/howto.png\" class=\"img-responsive\">\r\n</a>', 8, 1553606219, 1553958914, 'normal'),
(9, '边栏', 'sidebarad2', '边栏广告2', 'https://cdn.fastadmin.net/uploads/store/aliyun-sidebar.png', 'http://www.fastadmin.net', '<a href=\"https://www.fastadmin.net/go/aliyun\" rel=\"nofollow\" title=\"FastAdmin推荐企业服务器\" target=\"_blank\">\r\n        <img src=\"https://cdn.fastadmin.net/uploads/store/aliyun-sidebar.png\" class=\"img-responsive\" alt=\"\">\r\n</a>', 9, 1553606219, 1553958942, 'normal'),
(10, '焦点图', 'downloadfocus', '下载中心焦点图标题1', 'https://cdn.fastadmin.net/uploads/focus/4.jpg', '/', '', 10, 1553606219, 1553606257, 'normal'),
(11, '焦点图', 'downloadfocus', '下载中心焦点图标题2', 'https://cdn.fastadmin.net/uploads/focus/5.jpg', '/', '', 11, 1553606243, 1553606273, 'normal'),
(12, '焦点图', 'downloadfocus', '下载中心焦点图标题3', 'https://cdn.fastadmin.net/uploads/focus/6.jpg', '/', '', 12, 1553607965, 1553607965, 'normal'),
(13, '焦点图', 'downloadfocus', '下载中心焦点图标题4', 'https://cdn.fastadmin.net/uploads/focus/7.jpg', '/', '', 13, 1553608006, 1553608006, 'normal'),
(14, '焦点图', 'downloadfocus', '下载中心焦点图标题5', 'https://cdn.fastadmin.net/uploads/focus/8.jpg', '/', '', 14, 1553608049, 1553608049, 'normal'),
(15, '焦点图', 'downloadfocus', '下载中心焦点图标题6', 'https://cdn.fastadmin.net/uploads/focus/9.jpg', '/', '', 15, 1553608086, 1553608086, 'normal'),
(16, '焦点图', 'newsfocus', '新闻中心焦点图标题1', 'https://cdn.fastadmin.net/uploads/focus/10.jpg', '/', '', 16, 1553606219, 1553606257, 'normal'),
(17, '焦点图', 'newsfocus', '新闻中心焦点图标题2', 'https://cdn.fastadmin.net/uploads/focus/11.jpg', '/', '', 17, 1553606243, 1553606273, 'normal'),
(18, '焦点图', 'newsfocus', '新闻中心焦点图标题3', 'https://cdn.fastadmin.net/uploads/focus/12.jpg', '/', '', 18, 1553607965, 1553607965, 'normal'),
(19, '焦点图', 'newsfocus', '新闻中心焦点图标题4', 'https://cdn.fastadmin.net/uploads/focus/13.jpg', '/', '', 19, 1553608006, 1553608006, 'normal'),
(20, '焦点图', 'newsfocus', '新闻中心焦点图标题5', 'https://cdn.fastadmin.net/uploads/focus/14.jpg', '/', '', 20, 1553608049, 1553608049, 'normal'),
(21, '焦点图', 'newsfocus', '新闻中心焦点图标题6', 'https://cdn.fastadmin.net/uploads/focus/15.jpg', '/', '', 21, 1553608086, 1553608086, 'normal'),
(22, '焦点图', 'productfocus', '产品中心焦点图标题1', 'https://cdn.fastadmin.net/uploads/focus/16.jpg', '/', '', 22, 1553606219, 1553606257, 'normal'),
(23, '焦点图', 'productfocus', '产品中心焦点图标题2', 'https://cdn.fastadmin.net/uploads/focus/17.jpg', '/', '', 23, 1553606243, 1553606273, 'normal'),
(24, '焦点图', 'productfocus', '产品中心焦点图标题3', 'https://cdn.fastadmin.net/uploads/focus/18.jpg', '/', '', 24, 1553607965, 1553607965, 'normal'),
(25, '焦点图', 'productfocus', '产品中心焦点图标题4', 'https://cdn.fastadmin.net/uploads/focus/19.jpg', '/', '', 25, 1553608006, 1553608006, 'normal'),
(26, '焦点图', 'productfocus', '产品中心焦点图标题5', 'https://cdn.fastadmin.net/uploads/focus/20.jpg', '/', '', 26, 1553608049, 1553608049, 'normal'),
(27, '焦点图', 'productfocus', '产品中心焦点图标题6', 'https://cdn.fastadmin.net/uploads/focus/21.jpg', '/', '', 27, 1553608086, 1553608086, 'normal'),
(28, '边栏', 'sidebarad3', '边栏广告3', 'https://cdn.fastadmin.net/uploads/store/enterprisehost.png', 'http://www.fastadmin.net/go/aliyun', '', 9, 1553606219, 1553958942, 'normal');

--
-- 转存表中的数据 `__PREFIX__cms_diyform`
--

INSERT INTO `__PREFIX__cms_diyform` (`id`, `name`, `title`, `keywords`, `description`, `table`, `fields`, `needlogin`, `successtips`, `redirecturl`, `formtpl`, `diyname`, `createtime`, `updatetime`, `setting`, `status`) VALUES
(1, '站内留言', '发表留言', '留言板', '欢迎给我们留言反馈你的问题', 'cms_message', 'name,telephone,qq,content,image', 0, '留言已成功提交，我们会在第一时间进行处理', '', 'diyform.html', 'message', 1540091957, 1545931244, '', 'normal');

--
-- 转存表中的数据 `__PREFIX__cms_fields`
--

INSERT INTO `__PREFIX__cms_fields` (`id`, `model_id`, `diyform_id`, `name`, `type`, `title`, `content`, `defaultvalue`, `rule`, `msg`, `ok`, `tip`, `decimals`, `length`, `minimum`, `maximum`, `extend`, `weigh`, `createtime`, `updatetime`, `iscontribute`, `isfilter`, `status`) VALUES
(1, 0, 1, 'name', 'string', '姓名', 'value1|title1\r\nvalue2|title2', '', 'required', '', '', '', 0, 50, 0, 0, '', 136, 1540110334, 1540110334, 1, 0, 'normal'),
(2, 0, 1, 'telephone', 'string', '手机', 'value1|title1\r\nvalue2|title2', '', 'required,mobile', '', '', '', 0, 50, 0, 0, '', 135, 1540110369, 1540110369, 1, 0, 'normal'),
(3, 0, 1, 'qq', 'string', 'QQ', 'value1|title1\r\nvalue2|title2', '', 'digits', '', '', '', 0, 30, 0, 0, '', 134, 1540110394, 1540110394, 1, 0, 'normal'),
(4, 0, 1, 'content', 'editor', '内容', 'value1|title1\r\nvalue2|title2', '', 'required', '', '', '', 0, 255, 0, 0, '', 133, 1540110415, 1540110415, 1, 0, 'normal'),
(5, 1, 0, 'author', 'string', '作者', 'value1|title1\r\nvalue2|title2', '', '', '', '', '', 0, 50, 0, 0, '', 136, 1508990735, 1553759396, 1, 1, 'normal'),
(6, 2, 0, 'productdata', 'images', '产品列表', 'value1|title1\r\nvalue2|title2', '', 'required', '', '', '', 0, 1500, 0, 20, '', 139, 1508992518, 1508992518, 1, 1, 'normal'),
(7, 1, 0, 'price', 'number', '价格', 'value1|title1\r\nvalue2|title2', '0', '', '', '', '', 2, 10, 0, 0, '', 140, 1508992093, 1553759366, 1, 1, 'normal'),
(8, 0, 1, 'image', 'image', '图片', 'value1|title1\r\nvalue2|title2', '', '', '', '', '', 0, 255, 0, 0, '', 132, 1545931244, 1553996445, 1, 0, 'normal'),
(9, 3, 0, 'os', 'checkbox', '操作系统', 'windows|Windows\r\nlinux|Linux\r\nmac|Mac\r\nubuntu|Ubuntu', '', '', '', '', '', 0, 255, 0, 0, '', 143, 1553508185, 1553508185, 1, 1, 'normal'),
(10, 3, 0, 'version', 'string', '最新版本', 'value1|title1\r\nvalue2|title2', '', '', '', '', '', 0, 255, 0, 0, '', 144, 1553508231, 1553775383, 1, 0, 'normal'),
(11, 3, 0, 'filesize', 'string', '文件大小', 'value1|title1\r\nvalue2|title2', '', '', '', '', '', 0, 255, 0, 0, '', 145, 1553508273, 1553775381, 1, 0, 'normal'),
(12, 3, 0, 'language', 'checkbox', '语言', 'zh-cn|中文\r\nen|英文', '', '', '', '', '', 0, 255, 0, 0, '', 146, 1553508324, 1553775371, 1, 1, 'normal'),
(13, 3, 0, 'downloadurl', 'array', '下载地址', 'local|本地下载地址\r\nbaidu|百度网盘地址', '', '', '', '', '', 0, 255, 0, 0, '', 147, 1553508466, 1553775368, 1, 0, 'normal'),
(14, 3, 0, 'screenshots', 'images', '预览截图', 'value1|title1\r\nvalue2|title2', '', '', '', '', '', 0, 1500, 0, 0, '', 148, 1553509260, 1553775364, 1, 0, 'normal'),
(15, 3, 0, 'price', 'number', '价格', 'value1|title1\r\nvalue2|title2', '0', '', '', '', '', 2, 10, 0, 0, '', 149, 1553527695, 1553775363, 1, 0, 'normal'),
(16, 3, 0, 'downloads', 'string', '下载次数', 'value1|title1\r\nvalue2|title2', '0', '', '', '', '', 0, 10, 0, 0, '', 150, 1553744995, 1553775359, 1, 0, 'normal');

--
-- 转存表中的数据 `__PREFIX__cms_model`
--

INSERT INTO `__PREFIX__cms_model` (`id`, `name`, `table`, `fields`, `channeltpl`, `listtpl`, `showtpl`, `createtime`, `updatetime`, `setting`) VALUES
(1, '新闻', 'cms_addonnews', 'author,price', 'channel_news.html', 'list_news.html', 'show_news.html', 1508990659, 1553759313, '{\"contibutefields\":[\"image\",\"tags\",\"content\"]}'),
(2, '产品', 'cms_addonproduct', 'productdata', 'channel_product.html', 'list_product.html', 'show_product.html', 1508992445, 1508992445, '{\"contibutefields\":[\"image\",\"tags\",\"content\"]}'),
(3, '下载', 'cms_addondownload', 'os,version,filesize,language,downloadurl,screenshots,price,downloads', 'channel_download.html', 'list_download.html', 'show_download.html', 1553507913, 1553744995, '{\"contibutefields\":[\"image\",\"tags\",\"content\"]}');

--
-- 转存表中的数据 `__PREFIX__cms_page`
--

INSERT INTO `__PREFIX__cms_page` (`id`, `category_id`, `type`, `title`, `keywords`, `description`, `flag`, `image`, `content`, `icon`, `views`, `likes`, `dislikes`, `comments`, `diyname`, `showtpl`, `createtime`, `updatetime`, `weigh`, `status`) VALUES
(1, 0, 'page', 'FastAdmin - 基于ThinkPHP5和Bootstrap的极速后台开发框架', '', '', '', '', '<p>FastAdmin是一款基于ThinkPHP5+Bootstrap的极速后台开发框架。</p><h2 style=\"font-family:&quot;PingFang SC&quot;;white-space:normal;\">主要特性</h2><ul><li><p>基于<code>Auth</code>验证的权限管理系统</p><ul><li>支持无限级父子级权限继承，父级的管理员可任意增删改子级管理员及权限设置</li><li>支持单管理员多角色</li><li>支持管理子级数据或个人数据</li></ul></li><li><p>强大的一键生成功能</p><ul><li>一键生成CRUD,包括控制器、模型、视图、JS、语言包、菜单等</li><li>一键压缩打包JS和CSS文件，一键CDN静态资源部署</li><li>一键生成控制器菜单和规则</li><li>一键生成API接口文档</li></ul></li><li><p>完善的前端功能组件开发</p><ul><li>基于<code>AdminLTE</code>二次开发</li><li>基于<code>Bootstrap</code>开发，自适应手机、平板、PC</li><li>基于<code>RequireJS</code>进行JS模块管理，按需加载</li><li>基于<code>Less</code>进行样式开发</li><li>基于<code>Bower</code>进行前端组件包管理</li></ul></li><li><p>强大的插件扩展功能，在线安装卸载升级插件</p></li><li><p>通用的会员模块和API模块</p></li><li><p>共用同一账号体系的Web端会员中心权限验证和API接口会员权限验证</p></li><li><p>二级域名部署支持，同时域名支持绑定到插件</p></li><li><p>多语言支持，服务端及客户端支持</p></li><li><p>强大的第三方模块支持(<a href=\"https://www.fastadmin.net/store/cms.html\">CMS</a>、<a href=\"https://www.fastadmin.net/store/blog.html\">博客</a>、<a href=\"https://www.fastadmin.net/store/docs.html\">文档生成</a>)</p></li><li><p>整合第三方短信接口(阿里云、腾讯云短信)</p></li><li><p>无缝整合第三方云存储(七牛、阿里云OSS、又拍云)功能</p></li><li><p>第三方富文本编辑器支持(Summernote、Tinymce、百度编辑器)</p></li><li><p>第三方登录(QQ、微信、微博)整合</p></li><li><p>Ucenter整合第三方应用</p></li></ul><h2 style=\"font-family:&quot;PingFang SC&quot;;white-space:normal;\">安装使用</h2><p><a href=\"https://doc.fastadmin.net/\">https://doc.fastadmin.net</a></p><h2 style=\"font-family:&quot;PingFang SC&quot;;white-space:normal;\">在线演示</h2><p><a href=\"https://demo.fastadmin.net/\">https://demo.fastadmin.net</a></p><p>用户名：admin</p><p>密&emsp;码：123456</p><p>提&emsp;示：演示站数据无法进行修改，请下载源码安装体验全部功能</p><h2 style=\"font-family:&quot;PingFang SC&quot;;white-space:normal;\">界面截图</h2><p><img src=\"https://gitee.com/uploads/images/2017/0411/113717_e99ff3e7_10933.png\" alt=\"控制台\" referrerpolicy=\"no-referrer\" /></p><h2 style=\"font-family:&quot;PingFang SC&quot;;white-space:normal;\">问题反馈</h2><p>在使用中有任何问题，请使用以下联系方式联系我们</p><p>交流社区:&nbsp;<a href=\"https://forum.fastadmin.net/\">https://forum.fastadmin.net</a></p><p>QQ群:&nbsp;<a href=\"https://jq.qq.com/?_wv=1027&amp;k=487PNBb\">636393962</a>(满)&nbsp;<a href=\"https://jq.qq.com/?_wv=1027&amp;k=5ObjtwM\">708784003</a>(满)&nbsp;<a href=\"https://jq.qq.com/?_wv=1027&amp;k=59qjU2P\">964776039</a>(3群)</p><p>Email: (karsonzhang#163.com, 把#换成@)</p><p>Github:&nbsp;<a href=\"https://github.com/karsonzhang/fastadmin\" target=\"_blank\" class=\"url\">https://github.com/karsonzhang/fastadmin</a></p><p>Gitee:&nbsp;<a href=\"https://gitee.com/karson/fastadmin\" target=\"_blank\" class=\"url\">https://gitee.com/karson/fastadmin</a></p><h2 style=\"font-family:&quot;PingFang SC&quot;;white-space:normal;\">特别鸣谢</h2><p>感谢以下的项目,排名不分先后</p><p>ThinkPHP：<a href=\"http://www.thinkphp.cn/\">http://www.thinkphp.cn</a></p><p>AdminLTE：<a href=\"https://adminlte.io/\">https://adminlte.io</a></p><p>Bootstrap：<a href=\"http://getbootstrap.com/\">http://getbootstrap.com</a></p><p>jQuery：<a href=\"http://jquery.com/\">http://jquery.com</a></p><p>Bootstrap-table：<a href=\"https://github.com/wenzhixin/bootstrap-table\" target=\"_blank\" class=\"url\">https://github.com/wenzhixin/bootstrap-table</a></p><p>Nice-validator:&nbsp;<a href=\"https://validator.niceue.com/\">https://validator.niceue.com</a></p><p>SelectPage:&nbsp;<a href=\"https://github.com/TerryZ/SelectPage\" target=\"_blank\" class=\"url\">https://github.com/TerryZ/SelectPage</a></p><h2 style=\"font-family:&quot;PingFang SC&quot;;white-space:normal;\">版权信息</h2><p>FastAdmin遵循Apache2开源协议发布，并提供免费使用。</p><p>本项目包含的第三方源码和二进制文件之版权信息另行标注。</p><p>版权所有Copyright &copy; 2017-2018 by FastAdmin (<a href=\"https://www.fastadmin.net/\">https://www.fastadmin.net</a>)</p><p>All rights reserved。</p>', '', 547, 225, 0, 0, 'aboutus', 'page', 1508933935, 1553769449, 1, 'normal');
COMMIT;

--
-- 旧版本修复表结构
--
ALTER TABLE `__PREFIX__cms_order` MODIFY COLUMN `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID' FIRST,ADD COLUMN `orderid` varchar(50) NULL COMMENT '订单ID' AFTER `id`;

--
-- 旧版本修复表结构
--
ALTER TABLE `__PREFIX__cms_page` ADD COLUMN `likes` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '点赞' AFTER `views`,ADD COLUMN `dislikes` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '点踩' AFTER `likes`;

--
-- 旧版本修复表结构
--
ALTER TABLE `__PREFIX__cms_archives` ADD COLUMN `style` varchar(100) NULL DEFAULT '' COMMENT '样式' AFTER `title`;

--
-- 旧版本修复表结构
--
ALTER TABLE `__PREFIX__cms_archives` ADD COLUMN `special_ids` int(10) NOT NULL DEFAULT '0' COMMENT '专题ID' AFTER `model_id`;

--
-- 旧版本修复表结构
--
ALTER TABLE `__PREFIX__cms_message` ADD COLUMN `image` varchar(255) NULL DEFAULT '' COMMENT '图片' AFTER `content`;

--
-- 旧版本修复表结构
--
ALTER TABLE `__PREFIX__cms_channel` ADD COLUMN `isnav` tinyint(1) NULL DEFAULT '1' COMMENT '是否导航显示' AFTER `iscontribute`;

--
-- 旧版本修复表结构
--
ALTER TABLE `__PREFIX__cms_comment` MODIFY COLUMN `type` enum('archives','page','special') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'archives' COMMENT '类型' AFTER `user_id`;

--
-- 旧版本修复表结构
--
ALTER TABLE `__PREFIX__cms_block` ADD COLUMN `weigh` int(10) NULL DEFAULT 0 COMMENT '权重' AFTER `content`;
ALTER TABLE `__PREFIX__cms_special` ADD COLUMN `andor` enum('and','or') NULL DEFAULT 'or' COMMENT '索引与或' AFTER `tag_ids`;

--
-- 旧版本修复表结构，添加SEO标题
--
ALTER TABLE `__PREFIX__cms_archives` ADD COLUMN `seotitle` varchar(255) NULL DEFAULT '' COMMENT 'SEO标题' AFTER `image`;
ALTER TABLE `__PREFIX__cms_channel` ADD COLUMN `seotitle` varchar(255) NULL DEFAULT '' COMMENT 'SEO标题' AFTER `flag`;
ALTER TABLE `__PREFIX__cms_diyform` ADD COLUMN `seotitle` varchar(255) NULL DEFAULT '' COMMENT 'SEO标题' AFTER `title`;
ALTER TABLE `__PREFIX__cms_page` ADD COLUMN `seotitle` varchar(255) NULL DEFAULT '' COMMENT 'SEO标题' AFTER `title`;
ALTER TABLE `__PREFIX__cms_special` ADD COLUMN `seotitle` varchar(255) NULL DEFAULT '' COMMENT 'SEO标题' AFTER `diyname`;
ALTER TABLE `__PREFIX__cms_special` MODIFY COLUMN `nums` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文档数量' AFTER `archives`, ADD COLUMN `keywords` varchar(255) NULL COMMENT '关键字' AFTER `nums`, ADD COLUMN `description` varchar(255) NULL COMMENT '描述' AFTER `keywords`;
ALTER TABLE `__PREFIX__cms_archives` ADD COLUMN `isguest` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '是否访客访问' AFTER `diyname`,ADD COLUMN `iscomment` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '是否允许评论' AFTER `isguest`;

--
-- 1.2.0
-- 旧版本修复表结构，添加副栏目、多专题、游客访问、评论控制、数据权限
--
ALTER TABLE `__PREFIX__cms_diyform` ADD COLUMN `admin_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '管理员ID' AFTER `id`;
ALTER TABLE `__PREFIX__cms_page` ADD COLUMN `admin_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '管理员ID' AFTER `category_id`;
ALTER TABLE `__PREFIX__cms_special` ADD COLUMN `admin_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '管理员ID' AFTER `id`,ADD COLUMN `iscomment` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '是否允许评论' AFTER `comments`;
ALTER TABLE `__PREFIX__cms_archives` CHANGE COLUMN `special_id` `special_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '专题ID集合' AFTER `model_id`;
ALTER TABLE `__PREFIX__cms_archives` ADD COLUMN `admin_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '管理员ID' AFTER `special_ids`,ADD COLUMN `channel_ids` varchar(255) NULL DEFAULT '' COMMENT '副栏目ID集合' AFTER `channel_id`;
ALTER TABLE `__PREFIX__cms_page` ADD COLUMN `iscomment` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '是否允许评论' AFTER `showtpl`;
ALTER TABLE `__PREFIX__cms_block` ADD COLUMN `begintime` int(10) NULL COMMENT '开始时间' AFTER `updatetime`,ADD COLUMN `endtime` int(10) NULL COMMENT '结束时间' AFTER `begintime`;
ALTER TABLE `__PREFIX__cms_tags` ADD COLUMN `seotitle` varchar(100) DEFAULT '' COMMENT 'SEO标题' AFTER `nums`,ADD COLUMN `keywords` varchar(255) DEFAULT NULL COMMENT '关键字' AFTER `seotitle`,ADD COLUMN `description` varchar(255) DEFAULT NULL COMMENT '描述' AFTER `keywords`;
ALTER TABLE `__PREFIX__cms_comment` ADD COLUMN `deletetime` int(10) NULL COMMENT '删除时间' AFTER `updatetime`;
ALTER TABLE `__PREFIX__cms_page` ADD COLUMN `deletetime` int(10) NULL COMMENT '删除时间' AFTER `updatetime`;
ALTER TABLE `__PREFIX__cms_special` ADD COLUMN `deletetime` int(10) NULL COMMENT '删除时间' AFTER `updatetime`;
ALTER TABLE `__PREFIX__cms_archives` MODIFY COLUMN `flag` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '标志' AFTER `style`;
ALTER TABLE `__PREFIX__cms_special` MODIFY COLUMN `flag` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '标志' AFTER `andor`;
ALTER TABLE `__PREFIX__cms_page` MODIFY COLUMN `flag` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '标志' AFTER `description`;
ALTER TABLE `__PREFIX__cms_channel` MODIFY COLUMN `flag` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '标志' AFTER `image`;
ALTER TABLE `__PREFIX__cms_special` MODIFY COLUMN `tag_ids` varchar(1500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '标签ID集合' AFTER `title`;

--
-- 1.2.1
-- 旧版本修复表结构，添加组图
--
ALTER TABLE `__PREFIX__cms_archives` ADD COLUMN `images` varchar(1500) NULL DEFAULT '' COMMENT '组图' AFTER `image`;
