
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__recruit_news`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__recruit_news` (
  `id` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '类型',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '图片',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '链接',
  `content` mediumtext COMMENT '内容',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  `baoming` enum('news','baoming') NOT NULL DEFAULT 'news' COMMENT '是否可报名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='区块表';

-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__recruit_news`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__recruit_workforce` (
  `id` int(10) NOT NULL COMMENT 'ID',
  `village` varchar(100) DEFAULT '' COMMENT '村名',
  `name` varchar(100) DEFAULT '' COMMENT '姓名',
  `sex` enum('0','1') NOT NULL DEFAULT '1' COMMENT '性别:0=女,1=男 ',
  `sfzno` varchar(50) DEFAULT '' COMMENT '身份证号',
  `education` enum('0','1','2','3','4','5','6','7') NOT NULL DEFAULT '4' COMMENT '学历:0=文盲,1=小学,2=初中,3=高中,4=中专,5=大专,6=本科,7=研究生及以上',
  `place` varchar(100) DEFAULT '' COMMENT ' 务工地点',
  `salary` int(8) DEFAULT NULL COMMENT '月工资',
  `skill` varchar(100) DEFAULT NULL COMMENT '技能特长',
  `intent` varchar(100) DEFAULT NULL COMMENT '求职意向',
  `tel` varchar(50) DEFAULT '' COMMENT '联系电话',
  `content` text COMMENT '备注',
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `collect` varchar(50) DEFAULT '' COMMENT '收集人',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(11) DEFAULT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='劳动力信息数据库';

ALTER TABLE `__PREFIX__recruit_workforce`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `sfzno` (`sfzno`),
  ADD KEY `tel` (`tel`);
  
ALTER TABLE `__PREFIX__recruit_workforce`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID';

-- --------------------------------------------------------

--
-- 转存表中的数据 `__PREFIX__recruit_news`
--

INSERT INTO `__PREFIX__recruit_news` VALUES (1,'focus','+','找活+ 邀请新用户，上传简历，即可获得红包！亲，还在等什么呢?!!','/uploads/20180811/9607e5ee575356a746f1ab65ba3e612c.png','','<section>\r\n<p><img class=\"\" style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://mmbiz.qpic.cn/mmbiz_png/NrfddfmDCnw5uR6p07pic1yic4k3prAM7XWBxJFzea3lV19SRicCBtgYuaKJPVLMQGmQL8yfqnCic0xpI4kVBxc5Pg/640?wx_fmt=png&amp;tp=webp&amp;wxfrom=5&amp;wx_lazy=1\" data-ratio=\"0.518840579710145\" data-type=\"png\" data-copyright=\"0\" data-s=\"300,640\" data-w=\"690\" data-src=\"https://mmbiz.qpic.cn/mmbiz_png/NrfddfmDCnw5uR6p07pic1yic4k3prAM7XWBxJFzea3lV19SRicCBtgYuaKJPVLMQGmQL8yfqnCic0xpI4kVBxc5Pg/640?wx_fmt=png\" data-fail=\"0\" /></p>\r\n<section>\r\n<section>\r\n<section>\r\n<p><strong>新用户拿1-5元红包</strong></p>\r\n</section>\r\n</section>\r\n</section>\r\n</section>\r\n<p><strong>【活动时间】</strong></p>\r\n<p>2018 年7月1日~7月31日</p>\r\n<p><strong>【活动内容】</strong></p>\r\n<p>每位新用户，在完成昵称和头像设置后。可获得微信小程序发布的1-5元随机红包口令一个。</p>\r\n<p>&nbsp;</p>\r\n<section>\r\n<section>\r\n<section>\r\n<section>\r\n<p><strong>老用户拉新红包</strong></p>\r\n</section>\r\n</section>\r\n</section>\r\n</section>\r\n<p><strong>【活动时间】</strong></p>\r\n<p>2018年7月1日~7月31日</p>\r\n<p><strong>【活动内容】</strong></p>\r\n<p>关注微信公众号&ldquo;朝时&rdquo;，转发&ldquo;邀请好友及新用户送红包活动&rdquo;至朋友圈。即可领取1-2元红包一个。每人限一次。每日送出1000个红包，超出后不再赠送。</p>\r\n<p>如转发文章超过20人次点击，额外可获得1-5元红包一个。</p>\r\n<p>转发送红包活动无注册任务。转发红包即时发送。阅读红包待阅读次数达标后自动发送。</p>\r\n<p>本活动最终解释权归微金所所有。</p>',1529475221,1533464751,'normal','baoming');

-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__recruit_resumedelivery`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__recruit_resumedelivery` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `re_id` int(11) NOT NULL DEFAULT '0' COMMENT '简历id',
  `re_name` varchar(50) NOT NULL DEFAULT '' COMMENT '简历姓名',
  `re_tel` varchar(20) NOT NULL DEFAULT '' COMMENT '简历电话',
  `job_id` int(11) NOT NULL DEFAULT '0' COMMENT '职位id',
  `com_name` varchar(100) NOT NULL DEFAULT '' COMMENT '公司名称',
  `job_name` varchar(100) NOT NULL DEFAULT '' COMMENT '职位名称',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '归属人',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='简历投递表';

-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__recruit_company`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__recruit_company` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '企业名称',
  `tel` varchar(100) NOT NULL DEFAULT '' COMMENT '企业电话',
  `no` varchar(100) NOT NULL DEFAULT '' COMMENT '工商注册号',
  `xinzhi` enum('0','1','2','3','4') NOT NULL DEFAULT '1' COMMENT '企业性质:0=国有企业,1=私营企业,2=中外合作企业,3=中外合资企业,4=外商独资企业',
  `adress` varchar(200) DEFAULT '' COMMENT '地址',
  `content` text COMMENT '企业描述信息',
  `cimage` varchar(100) NOT NULL DEFAULT '' COMMENT '企业头像',
  `cimages` varchar(800) DEFAULT NULL COMMENT '企业照片',
  `user_id` int(11) DEFAULT NULL COMMENT '企业归属人',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='企业信息';

--
-- 转存表中的数据 `__PREFIX__recruit_company`
--

INSERT INTO `__PREFIX__recruit_company` VALUES (1,'亳州建元信息科技有限公司','15551219817','91341600MA2RP25GXT','1','亳州芜湖产业园3号楼','<p><strong>【所属行业】</strong>商务服务业</p>','/uploads/20180811/f27a183b0eaa937274d6af027f2d736d.jpg','/uploads/20180811/fb99eaa815c111ade84c525b6c0ca4d9.jpg,/uploads/20180811/70762bd6e33dd733cec83fca591e5296.jpg,/uploads/20180811/af3da5c14ea3b5631cb7a39785912866.jpg',18,1531902710,1533969125),(2,'南京觅阿姨家政服务有限公司','86288715','3201061972635182','1','南京市鼓楼区漓江路31号','','/uploads/20180811/913f6e03e780fed3cfec67953d1918d8.gif','/uploads/20180811/4b7765ac664aaa036986513505f7789e.jpg',2,1533969496,1533969603);

-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__recruit_job`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__recruit_job` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `c_id` int(11) NOT NULL DEFAULT '0' COMMENT '对应所属公司的id',
  `name` char(100) NOT NULL DEFAULT '' COMMENT '职位名称',
  `age` enum('0','1','2','3','4') NOT NULL DEFAULT '0' COMMENT '年龄要求:0=无要求,1=18-30岁,2=30-45岁,3=45-50岁,4=其他',
  `stay` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '住宿:0=不提供,1=提供,2=提供夫妻房',
  `food` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT '伙食:0=不提供,1=只提供午饭,2=提供三餐,3=有餐补',
  `safe` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT '社保:0=不缴纳,1=缴纳三险,2=缴纳五险,3=缴纳五险一金',
  `neednum` int(6) DEFAULT NULL COMMENT '招聘人数',
  `city_id` int(10) NOT NULL DEFAULT '0' COMMENT '工作地点',
  `education` enum('0','1','2','3','4','5','6') NOT NULL DEFAULT '0' COMMENT '学历要求:0=无要求,1=小学,2=初中,3=高中,4=大专,5=本科,6=研究生及以上',
  `gold1` int(10) NOT NULL DEFAULT '3000' COMMENT '薪资起',
  `gold2` int(10) NOT NULL DEFAULT '3000' COMMENT '薪资止',
  `content` text COMMENT '职位描述',
  `user_id` int(11) DEFAULT NULL COMMENT '职位归属人',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='职位信息';

--
-- 转存表中的数据 `__PREFIX__recruit_job`
--

INSERT INTO `__PREFIX__recruit_job` VALUES (1,1,'建元电话催收专员（单休） ','4','1','2','3',100,19,'3',3000,5000,'<p><strong style=\"color: #e33737;\">工作时间：</strong>8：30-11：30，13:00-17:30</p>\r\n<p><u><em>午休一个半小时，适当加班，加班时间19:00-19:30。</em></u></p>\r\n<p><strong style=\"color: #e33737;\">无责任底薪：</strong>2000到3000不等，一般可拿到2500，加上提成平均薪资3500-5000，上不封顶。</p>\r\n<p><span style=\"color: #e33737;\"><strong>公司提供：</strong></span>标准六人间宿舍(热水器，空调，电视）</p>\r\n<p>园区有两个食堂，</p>\r\n<p>入职半年购买五险，满一年购买住房公积金。</p>\r\n<p><span style=\"color: #e33737;\"><strong>招聘要求:</strong></span>18-35岁 中专学历 有进取精神</p>\r\n<p><br /><strong style=\"color: #e33737;\">面试时间:</strong>8.30-11.00 下午1.00-5.00</p>',18,1531903289,1533715076),(2,2,'月嫂','2','1','3','1',10,19,'2',6000,9500,'内部培训',2,1533969551,1533969551);

-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__recruit_jobfair`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__recruit_jobfair` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) DEFAULT '0' COMMENT '关联招聘会id',
  `block_title` varchar(100) DEFAULT '' COMMENT '招聘会标题',
  `user_id` int(11) DEFAULT NULL COMMENT '报名人id',
  `tname` varchar(100) NOT NULL DEFAULT '' COMMENT '公司名/姓名',
  `ttel` varchar(20) NOT NULL DEFAULT '' COMMENT '电话',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '报名时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='招聘会报名';

--
-- 转存表中的数据 `__PREFIX__recruit_jobfair`
--

INSERT INTO `__PREFIX__recruit_jobfair` VALUES (1,1,'找活+ 邀请新用户，上传简历，即可获得红包！亲，还在等什么呢?!!',56,'sadsad','sdas 是深V',1532663953,1532663953);

-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__recruit_opencity`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__recruit_opencity` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `city` varchar(100) NOT NULL DEFAULT '' COMMENT '省市',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='开放地区';

--
-- 转存表中的数据 `__PREFIX__recruit_opencity`
--

INSERT INTO `__PREFIX__recruit_opencity` VALUES (19,'安徽省/宿州市',0,1529569234,1529569248),(20,'安徽省/蚌埠市',4,1531815749,1531815749);

-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__recruit_resume`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__recruit_resume` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '个人电话',
  `birthday` date NOT NULL DEFAULT '1980-01-01' COMMENT '生日',
  `sex` enum('0','1') NOT NULL DEFAULT '1' COMMENT '性别:0=女,1=男',
  `education` enum('0','1','2','3','4','5','6') NOT NULL DEFAULT '4' COMMENT '学历:0=文盲,1=小学,2=初中,3=高中,4=大专,5=本科,6=研究生及以上',
  `native_place` varchar(50) NOT NULL DEFAULT '' COMMENT '籍贯',
  `gold1` int(10) NOT NULL DEFAULT '3000' COMMENT '薪资起',
  `gold2` int(10) NOT NULL DEFAULT '3000' COMMENT '薪资止',
  `work_city` int(8) NOT NULL DEFAULT '0' COMMENT '工作城市',
  `content` text COMMENT '自我介绍',
  `c_avatar` varchar(100) NOT NULL DEFAULT '' COMMENT '头像照片',
  `user_id` int(11) DEFAULT NULL COMMENT '职位归属人',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='简历';

--
-- 转存表中的数据 `__PREFIX__recruit_resume`
--

INSERT INTO `__PREFIX__recruit_resume` VALUES (1,'you.you','13813913278','1980-01-01','1','4','安徽省/芜湖市',3000,5000,5,'<p>撒打算</p>','/uploads/20180811/088f86c846433555e0a97248cfca5f9c.png',1,1531121122,1533969165),(2,'lizard','13915908090','1980-04-01','0','2','安徽省/池州市',3000,4500,19,'11','/uploads/20180811/1e8bb96b552c948390fe575edbf2d5a9.jpg',2,1533961671,1533961671);

COMMIT;