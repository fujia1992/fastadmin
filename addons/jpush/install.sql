CREATE TABLE IF NOT EXISTS `__PREFIX__jpush_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `sendno` varchar(50) NOT NULL COMMENT '发送编号',
  `msg_id` varchar(50) NOT NULL COMMENT 'Message ID',
  `push_type` varchar(10) NOT NULL DEFAULT 'now' COMMENT '推送方式',
  `receiver` varchar(20) NOT NULL COMMENT '接受对象',
  `content` varchar(255) NOT NULL COMMENT '推送内容',
  `platform` varchar(20) NOT NULL COMMENT '推送平台',
  `createtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;