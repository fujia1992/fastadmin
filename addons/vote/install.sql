CREATE TABLE IF NOT EXISTS `__PREFIX__vote_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject_id` int(10) DEFAULT NULL COMMENT '主题ID',
  `player_id` int(10) DEFAULT NULL COMMENT '参赛人ID',
  `user_id` int(10) DEFAULT NULL COMMENT '会员ID',
  `nickname` varchar(100) DEFAULT NULL COMMENT '昵称',
  `content` varchar(1500) DEFAULT NULL COMMENT '内容',
  `ip` varchar(50) DEFAULT NULL COMMENT 'IP',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') DEFAULT 'hidden' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='投票评论表';

CREATE TABLE IF NOT EXISTS `__PREFIX__vote_fields` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `subject_id` int(10) NOT NULL DEFAULT '0' COMMENT '模型ID',
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
  `maximum` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '最大数量',
  `extend` varchar(255) NOT NULL DEFAULT '' COMMENT '扩展信息',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `model_id` (`subject_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='投票报名字段表';

CREATE TABLE IF NOT EXISTS `__PREFIX__vote_player` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject_id` int(10) unsigned DEFAULT '0' COMMENT '主题ID',
  `category_id` int(10) UNSIGNED NULL DEFAULT '0' COMMENT '分类ID',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `number` int(10) unsigned DEFAULT '1' COMMENT '编号',
  `nickname` varchar(100) DEFAULT NULL COMMENT '昵称',
  `intro` varchar(100) DEFAULT NULL COMMENT '介绍',
  `content` text COMMENT '内容',
  `image` varchar(100) DEFAULT NULL COMMENT '图片',
  `bgcolor` varchar(30) DEFAULT NULL COMMENT '背景颜色',
  `banner` varchar(100) DEFAULT NULL COMMENT '背景图片',
  `votes` int(10) unsigned DEFAULT '0' COMMENT '得票数',
  `views` int(10) unsigned DEFAULT '0' COMMENT '浏览次数',
  `comments` int(10) unsigned DEFAULT '0' COMMENT '评论数',
  `applydata` text COMMENT '报名信息',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `votetime` int(10) DEFAULT NULL COMMENT '投票时间',
  `memo` varchar(100) DEFAULT '' COMMENT '备注',
  `status` enum('normal','hidden', 'rejected') DEFAULT 'hidden' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `subject_id_2` (`subject_id`,`number`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='投票参赛表';

CREATE TABLE IF NOT EXISTS `__PREFIX__vote_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `subject_id` int(10) unsigned DEFAULT '0' COMMENT '投票主题ID',
  `player_id` int(10) unsigned DEFAULT '0' COMMENT '参赛人ID',
  `ip` varchar(50) DEFAULT NULL COMMENT 'IP',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`,`player_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='投票记录表';

CREATE TABLE IF NOT EXISTS `__PREFIX__vote_subject` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `image` varchar(100) DEFAULT NULL COMMENT '图片',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `rules` varchar(1500) DEFAULT NULL COMMENT '规则',
  `intro` varchar(255) DEFAULT NULL COMMENT '介绍',
  `content` text COMMENT '内容',
  `banner` varchar(1500) DEFAULT NULL COMMENT '背景图片',
  `players` int(10) unsigned DEFAULT '0' COMMENT '参赛人数',
  `votes` int(10) unsigned DEFAULT '0' COMMENT '累积投票数',
  `voters` int(10) unsigned DEFAULT '0' COMMENT '累积投票人数',
  `views` int(10) unsigned DEFAULT '0' COMMENT '累积访问次数',
  `pervotenums` int(10) unsigned DEFAULT '1' COMMENT '每天可投票数',
  `pervotelimit` int(10) unsigned DEFAULT '1' COMMENT '每天可为同一选手投票数',
  `limitarea` varchar(255) DEFAULT '' COMMENT '限制区域',
  `needlogin` tinyint(1) unsigned DEFAULT '0' COMMENT '是否需要登录',
  `onlywechat` tinyint(1) unsigned DEFAULT '0' COMMENT '是否只在微信',
  `diyname` varchar(50) DEFAULT NULL COMMENT '自定义名称',
  `playername` varchar(50) DEFAULT NULL COMMENT '参赛名称',
  `subjecttpl` varchar(50) DEFAULT NULL COMMENT '主题页模板',
  `playertpl` varchar(50) DEFAULT NULL COMMENT '详情页模板',
  `ranktpl` varchar(50) DEFAULT NULL COMMENT '排行榜页模板',
  `applytpl` varchar(50) DEFAULT '' COMMENT '报名页模板',
  `applyfields` varchar(1500) DEFAULT NULL COMMENT '报名字段',
  `iscomment` tinyint(1) unsigned DEFAULT '1' COMMENT '是否允许评论',
  `isapply` tinyint(1) unsigned DEFAULT '1' COMMENT '是否开放报名',
  `pagesize` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '分页大小',
  `begintime` int(10) DEFAULT NULL COMMENT '开始时间',
  `endtime` int(10) DEFAULT NULL COMMENT '结束时间',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `memo` varchar(100) DEFAULT '' COMMENT '备注',
  `status` enum('normal','hidden','expired','rejected') DEFAULT 'hidden' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='投票主题表';

CREATE TABLE `__PREFIX__vote_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject_id` int(10) unsigned DEFAULT '0' COMMENT '投票主题ID',
  `pid` int(10) unsigned DEFAULT '0' COMMENT '父类ID',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `image` varchar(100) DEFAULT NULL COMMENT '图片',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('hidden','normal') DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='投票分类表';

ALTER TABLE `__PREFIX__vote_subject` ADD COLUMN `applytpl` varchar(50) NULL DEFAULT '' COMMENT '报名页模板' AFTER `ranktpl`;
ALTER TABLE `__PREFIX__vote_subject` ADD COLUMN `applyfields` varchar(1500) NULL COMMENT '报名字段' AFTER `applytpl`;
ALTER TABLE `__PREFIX__vote_subject` ADD COLUMN `isapply` tinyint(1) unsigned DEFAULT '1' COMMENT '是否开放报名' AFTER `iscomment`;

ALTER TABLE `__PREFIX__vote_player` ADD COLUMN `applydata` text NULL COMMENT '报名信息' AFTER `comments`;
ALTER TABLE `__PREFIX__vote_player` ADD COLUMN `category_id` int(10) UNSIGNED NULL DEFAULT '0' COMMENT '分类ID' AFTER `subject_id`;
ALTER TABLE `__PREFIX__vote_subject` ADD COLUMN `pagesize` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '分页大小' AFTER `isapply`;

ALTER TABLE `__PREFIX__vote_player` ADD COLUMN `memo` varchar(100) DEFAULT '' COMMENT '备注' AFTER `votetime`;
ALTER TABLE `__PREFIX__vote_player` MODIFY COLUMN `status` enum('normal','hidden','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'hidden' COMMENT '状态' AFTER `memo`;

ALTER TABLE `__PREFIX__vote_subject` ADD COLUMN `user_id` int(10) DEFAULT '0' COMMENT '会员ID' AFTER `id`;
ALTER TABLE `__PREFIX__vote_subject` ADD COLUMN `memo` varchar(100) DEFAULT '' COMMENT '备注' AFTER `updatetime`;
ALTER TABLE `__PREFIX__vote_subject` MODIFY COLUMN `status` enum('normal','hidden','expired','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'hidden' COMMENT '状态' AFTER `updatetime`;
