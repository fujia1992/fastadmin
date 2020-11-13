
-- ----------------------------
-- Table structure for __PREFIX__kaoshi_exams
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kaoshi_exams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(11) unsigned NOT NULL COMMENT '管理员ID',
  `subject_id` mediumint(8) unsigned NOT NULL COMMENT '科目ID',
  `exam_name` varchar(120) NOT NULL COMMENT '卷名',
  `settingdata` text NOT NULL COMMENT '考卷设置',
  `questionsdata` text COMMENT '考卷题目',
  `pass` int(11) NOT NULL DEFAULT '0' COMMENT '及格线',
  `score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总分',
  `type` enum('2','1') NOT NULL DEFAULT '1' COMMENT '类型:1=随机组卷,2=自定义组卷',
  `keyword` varchar(240) NOT NULL COMMENT '关键字',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) unsigned NOT NULL COMMENT '更新时间',
  `deletetime` int(11) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `examstatus` (`status`),
  KEY `examtype` (`type`,`admin_id`),
  KEY `examsubject` (`subject_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for __PREFIX__kaoshi_plan
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kaoshi_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL COMMENT '科目ID',
  `exam_id` int(11) NOT NULL COMMENT '试卷ID',
  `plan_name` varchar(120) NOT NULL COMMENT '名称',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '考场类型:0=正式,1=学习',
  `hours` int(10) NOT NULL DEFAULT '60' COMMENT '学习时长',
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '考试次数',
  `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) unsigned NOT NULL COMMENT '更新时间',
  `deletetime` int(11) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `basicsubjectid` (`subject_id`),
  KEY `type` (`type`),
  KEY `end_time` (`endtime`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='考场表';

-- ----------------------------
-- Table structure for __PREFIX__kaoshi_subject
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kaoshi_subject` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(255) NOT NULL COMMENT '科目名称',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `deletetime` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for __PREFIX__kaoshi_user_exams
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kaoshi_user_exams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_plan_id` int(11) unsigned NOT NULL COMMENT '用户计划ID',
  `questionsdata` text NOT NULL COMMENT '题目',
  `answersdata` text COMMENT '答案',
  `real_answersdata` text NOT NULL COMMENT '正确答案',
  `scorelistdata` varchar(255) DEFAULT NULL COMMENT '题目得分',
  `score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总分',
  `status` enum('1','0') NOT NULL DEFAULT '0' COMMENT '状态:0=进行中,1=已完成',
  `usetime` int(11) NOT NULL DEFAULT '0' COMMENT '使用时间',
  `starttime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `lasttime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上一次答题时间',
  PRIMARY KEY (`id`)
)  ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for __PREFIX__kaoshi_user_plan
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__kaoshi_user_plan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `plan_id` int(11) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态:0=未完成,1=已完成',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `basicid` (`plan_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `__PREFIX__kaoshi_questions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL COMMENT '出题人ID',
  `subject_id` int(11) unsigned NOT NULL COMMENT '科目ID',
  `type` enum('3','2','1') NOT NULL DEFAULT '1' COMMENT '类型:1=单选题,2=多选题,3=判断题',
  `question` text NOT NULL COMMENT '题目',
  `selectdata` text NOT NULL COMMENT '选项',
  `selectnumber` tinyint(11) unsigned NOT NULL DEFAULT '0' COMMENT '选项数量',
  `answer` text NOT NULL COMMENT '答案',
  `describe` text COMMENT '答案解析',
  `level` enum('3','2','1') NOT NULL DEFAULT '1' COMMENT '等级:1=易,2=中,3=难',
`status` enum('2','1') NOT NULL DEFAULT '1' COMMENT '状态:1=显示,2=隐藏',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) unsigned NOT NULL COMMENT '更新时间',
  `deletetime` int(11) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `questioncreatetime` (`createtime`),
  KEY `questiontype` (`type`),
  KEY `questionstatus` (`status`),
  KEY `questionuserid` (`admin_id`),
  KEY `questionlevel` (`level`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='试题表';
BEGIN;
ALTER TABLE `__PREFIX__kaoshi_questions` ADD  `annex` varchar(255) DEFAULT NULL COMMENT '题目附件';
COMMIT ;
