CREATE TABLE `yj_user_lesson` (
  `lessonid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(11) DEFAULT NULL COMMENT '学员id',
  `course_type` int(11) DEFAULT NULL COMMENT '课时类型 60分钟课程 80分钟课程 45分钟辅助课',
  `courseid` int(11) DEFAULT NULL COMMENT '课时编号',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `lesson_count` int(11) NOT NULL DEFAULT '0' COMMENT '所剩余课时,每次签到此字段都会减少一节课 相应的有签到记录(流水)',
  `status` tinyint(1) DEFAULT '1' COMMENT '所属课程类型 因为不同的课程有不同的课时,学员在签到的时候可能选择的课程不一样，所以签到减课时的时候要判断选择的那种课程类型',
  `mark` text COMMENT '备注信息',
  PRIMARY KEY (`lessonid`),
  KEY `idx_cetate_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='学员课时表';


CREATE TABLE `yj_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增用户id',
  `umobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `user_name` varchar(100) DEFAULT NULL COMMENT '姓名',
  `user_age` varchar(100) DEFAULT NULL COMMENT '年龄',
  `user_address` varchar(100) DEFAULT NULL COMMENT '用户地址',
  `sign_type` varchar(100) DEFAULT NULL COMMENT '报名方式',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `expe_time` int(11) unsigned DEFAULT NULL COMMENT '上体验课时间',
  `sign_time` int(11) unsigned DEFAULT NULL COMMENT '报名时间',
  `is_expe` tinyint(11) unsigned DEFAULT NULL COMMENT '是否上过体验课 1是，2否',
  `is_sign` tinyint(1) DEFAULT NULL COMMENT '是否报名 1已报名，2未报名',
  `status` tinyint(1) DEFAULT '1' COMMENT '注册状态：1正常,2离开,3其他(可能休假或者暂时不来上课)',
  `mark` text COMMENT '备注信息',
  PRIMARY KEY (`uid`),
  KEY `idx_cetate_time` (`create_time`) USING BTREE,
  KEY `idx_umobile` (`umobile`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='用户基本信息表';

