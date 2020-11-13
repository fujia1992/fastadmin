-- ----------------------------
-- Table structure for __PREFIX__ykquest_answerer
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__ykquest_answerer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '禁用:0=否,1=是',
  `avatarimage` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '头像',
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '城市',
  `nickname` varchar(255) NOT NULL COMMENT '昵称',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  `deletetime` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='答卷者';

-- ----------------------------
-- Table structure for __PREFIX__ykquest_myanswer
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__ykquest_myanswer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL COMMENT '问卷编号',
  `answerer_id` int(11) NOT NULL COMMENT '答卷者编号',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  `deletetime` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='问卷用户关系表';

-- ----------------------------
-- Table structure for __PREFIX__ykquest_problem
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__ykquest_problem` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '题目',
  `option_type` enum('3','2','1','0') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '类型:0=单选题,1=多选题,2=下拉题,3=文本',
  `survey_id` int(11) NOT NULL COMMENT '问卷编号',
  `admin_id` int(11) NOT NULL COMMENT '填写人',
  `weigh` int(11) NOT NULL DEFAULT '0' COMMENT '权重',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  `deletetime` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='问题表';

-- ----------------------------
-- Table structure for __PREFIX__ykquest_reply
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__ykquest_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `survey_id` int(11) NOT NULL COMMENT '问卷编号',
  `problem_id` int(11) NOT NULL COMMENT '问题编号',
  `answerer_id` int(11) NOT NULL COMMENT '回答者',
  `content` varchar(255) DEFAULT NULL COMMENT '内容',
  `admin_id` int(11) DEFAULT NULL COMMENT '发起人',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  `deletetime` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='答卷表';

-- ----------------------------
-- Table structure for __PREFIX__ykquest_survey
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__ykquest_survey` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `admin_id` int(11) NOT NULL COMMENT '问卷发起人',
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '状态:0=关闭,1=开启',
  `starttime` int(11) NOT NULL COMMENT '开始时间',
  `endtime` int(11) NOT NULL COMMENT '结束时间',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `type_id` int(11) NOT NULL COMMENT '类型',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  `deletetime` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='问卷表';

-- ----------------------------
-- Table structure for __PREFIX__ykquest_toption
-- ----------------------------

CREATE TABLE IF NOT EXISTS `__PREFIX__ykquest_toption` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL,
  `problem_id` int(11) NOT NULL COMMENT '问题',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  `deletetime` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='选项表';

-- ----------------------------
-- Table structure for __PREFIX__ykquest_type
-- ----------------------------

CREATE TABLE IF NOT EXISTS `__PREFIX__ykquest_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `admin_id` int(11) NOT NULL COMMENT '填写者',
  `name` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  `deletetime` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='问卷类型表';


BEGIN;
ALTER TABLE `__PREFIX__ykquest_answerer`
ADD COLUMN `user_id` int(11)  DEFAULT NULL COMMENT '会员id';
COMMIT;

