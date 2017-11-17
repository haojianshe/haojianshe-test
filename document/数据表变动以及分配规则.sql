-- 使用前删除非素材的帖子和已删除的素材  能力模型素材
delete from ci_tweet where is_del=1 or type<>1;
delete from myb_capacitymodel_material where  `status`<>0;

-- 导入用户数据
INSERT INTO `myb_mis_user` (`mis_userid`, `mis_username`, `mis_realname`, `password`, `email`, `department`, `roleids`, `status`) VALUES 
 (100, 'taguser1',  'taguser1',  'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (101, 'taguser2',  'taguser2',  'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (102, 'taguser3',  'taguser3',  'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (103, 'taguser4',  'taguser4',  'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (104, 'taguser5',  'taguser5',  'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (105, 'taguser6',  'taguser6',  'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (106, 'taguser7',  'taguser7',  'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (107, 'taguser8',  'taguser8',  'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (108, 'taguser9',  'taguser9',  'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (109, 'taguser10', 'taguser10', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (110, 'taguser11', 'taguser11', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (111, 'taguser12', 'taguser12', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (112, 'taguser13', 'taguser13', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (113, 'taguser14', 'taguser14', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (114, 'taguser15', 'taguser15', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (115, 'taguser16', 'taguser16', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (116, 'taguser17', 'taguser17', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (117, 'taguser18', 'taguser18', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (118, 'taguser19', 'taguser19', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (119, 'taguser20', 'taguser20', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (120, 'taguser21', 'taguser21', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (121, 'taguser22', 'taguser22', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (122, 'taguser23', 'taguser23', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (123, 'taguser24', 'taguser24', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (124, 'taguser25', 'taguser25', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (125, 'taguser26', 'taguser26', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (126, 'taguser27', 'taguser27', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (127, 'taguser28', 'taguser28', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (128, 'taguser29', 'taguser29', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (129, 'taguser30', 'taguser30', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (130, 'taguser31', 'taguser31', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (131, 'taguser32', 'taguser32', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (132, 'taguser33', 'taguser33', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (133, 'taguser34', 'taguser34', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0),
 (134, 'taguser35', 'taguser35', 'e10adc3949ba59abbe56e057f20f883e', '', '', '2', 0);

-- 增加打标签字段 -- ci_tweet --myb_capacitymodel_material

ALTER TABLE `myb_capacitymodel_material`
ADD COLUMN `new_f_catalog_id`  int(11)  COMMENT '打完一级分类';

ALTER TABLE `myb_capacitymodel_material`
ADD COLUMN `new_s_catalog_id`  int(11)  COMMENT '打完二级分类';

ALTER TABLE `myb_capacitymodel_material`
ADD COLUMN `new_tags`  varchar(255) DEFAULT NULL COMMENT '打完标签';

ALTER TABLE `myb_capacitymodel_material`
ADD COLUMN `adminid`  int(11)  COMMENT '分配用户id';

ALTER TABLE `myb_capacitymodel_material`
ADD COLUMN `update_status`  int(11)  COMMENT '状态';

-- 帖子表
ALTER TABLE `ci_tweet`
ADD COLUMN `new_f_catalog_id`  int(11)  COMMENT '打完一级分类';

ALTER TABLE `ci_tweet`
ADD COLUMN `new_s_catalog_id`  int(11)  COMMENT '打完二级分类';

ALTER TABLE `ci_tweet`
ADD COLUMN `new_tags`  varchar(255) DEFAULT NULL  COMMENT '打完标签';

ALTER TABLE `ci_tweet`
ADD COLUMN `adminid`  int(11)  COMMENT '分配用户id';

ALTER TABLE `ci_tweet`
ADD COLUMN `update_status`  int(11)  COMMENT '状态';



-- 帖子素材 ci_tweet
--
-- 色彩
-- update
-- update ci_tweet set s_catalog="静物单体"  where s_catalog_id=101;
-- update ci_tweet set s_catalog="组合静物"  where s_catalog_id=100;
-- del 
-- update ci_tweet set s_catalog_id=null  where s_catalog_id=104 ; -- 大师作品
-- update ci_tweet set s_catalog_id=null  where s_catalog_id=105; -- 小色稿
-- update ci_tweet set s_catalog_id=null  where s_catalog_id=106; -- 单色塑造
--
-- 设计
-- update
-- update ci_tweet set s_catalog="命题装饰画"  where s_catalog_id=108;
-- update ci_tweet set s_catalog="命题创意速写"  where s_catalog_id=110;
--
-- 照片
-- del
-- update ci_tweet set s_catalog_id=null  where  s_catalog_id=116; -- 静物
-- update ci_tweet set s_catalog_id=null  where  s_catalog_id=118; -- 人物
-- update ci_tweet set s_catalog_id=null  where  s_catalog_id=120; -- 天气
--  update ci_tweet set s_catalog_id=null  where  s_catalog_id=121; -- 时间
-- update ci_tweet set s_catalog_id=null  where  s_catalog_id=122; -- 节日
--
-- 素描
-- del
-- update ci_tweet set s_catalog_id=null  where  s_catalog_id=132;-- 大师作品 
--
-- 速写
-- del
-- update ci_tweet set s_catalog_id=null  where  s_catalog_id=139; -- 大师作品
--
-- 创作
-- del
-- update ci_tweet set s_catalog_id=null  where   s_catalog_id=140; -- 材料
-- update ci_tweet set s_catalog_id=null  where   s_catalog_id=141; -- 颜色
-- update ci_tweet set s_catalog_id=null  where   s_catalog_id=142; -- 场景
-- update ci_tweet set s_catalog_id=null  where   s_catalog_id=143; -- 天气
-- update ci_tweet set s_catalog_id=null  where   s_catalog_id=144; -- 时间 
-- update ci_tweet set s_catalog_id=null  where   s_catalog_id=145; -- 节日
--
-- 能力模型素材 myb_capacitymodel_material
--
-- 色彩
-- update 能力模型素材未存储中文名
--
-- update set s_catalog="静物单体" myb_capacitymodel_material where s_catalog_id=101;
-- update set s_catalog="组合静物" myb_capacitymodel_material where s_catalog_id=100;
-- del 
-- update myb_capacitymodel_material set s_catalog_id=null  where s_catalog_id=104 ; -- 大师作品
-- update myb_capacitymodel_material set s_catalog_id=null  where s_catalog_id=105; -- 小色稿
-- update myb_capacitymodel_material set s_catalog_id=null  where  s_catalog_id=106; -- 单色塑造
--
--
--
--
-- 素描
-- del
-- update myb_capacitymodel_material set s_catalog_id=null  where  s_catalog_id=132;-- 大师作品 
--
-- 速写
-- del
-- update myb_capacitymodel_material set s_catalog_id=null  where  s_catalog_id=139; -- 大师作品
--


-- 去掉测试数据
-- update ci_tweet set adminid=null,new_f_catalog_id=null,new_s_catalog_id=null,update_status=null,new_tags=null where type=1 and is_del=0;
-- 
-- 
-- update myb_capacitymodel_material set adminid=null,new_f_catalog_id=null,new_s_catalog_id=null,update_status=null,new_tags=null;



-- 修改帖子表  一级二级分类  数据
update ci_tweet set new_f_catalog_id=f_catalog_id,new_s_catalog_id=s_catalog_id where s_catalog_id not in (
104,
105,
106,
116,
118,
120,
121,
122,
132,
139,
140,
141,
142,
143,
144,
145
);

update ci_tweet set new_f_catalog_id=f_catalog_id where s_catalog_id  in (
104,
105,
106,
116,
118,
120,
121,
122,
132,
139,
140,
141,
142,
143,
144,
145
);


-- 能力模型表 一级分类 二级分类导入
update myb_capacitymodel_material set new_f_catalog_id=f_catalog_id,new_s_catalog_id=s_catalog_id where s_catalog_id not in (
132,
104,
105,
106,
139
);

-- 能力模型表 一级分类 
update myb_capacitymodel_material set new_f_catalog_id=f_catalog_id where s_catalog_id  in (
132,
104,
105,
106,
139
);



-- 获取总的分类的数量 然后除以用户数来获得limit
 select count(*) from ci_tweet where f_catalog_id=1 and s_catalog_id=104 and type=1 and is_del=0;
 select count(*) from myb_capacitymodel_material where f_catalog_id=1 and s_catalog_id=104  and status=0;


-- $number = (分类+二级分类总数) / 用户数量

-- 帖子素材 执行语句 f_catalog_id (1,2,3,4,5,6)
-- update ci_tweet set adminid=$userid where f_catalog_id=$f_catalog_id and s_catalog_id=$s_catalog_id and adminid is NULL and type=1 and is_del=0 order by tid desc LIMIT $number;

-- update ci_tweet set adminid=100 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=101 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=102 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=103 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=104 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=105 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=106 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=107 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=108 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=109 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=110 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=111 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=112 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=113 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=114 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=115 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=116 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=117 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=118 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=119 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=120 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=121 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=122 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=123 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=124 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=125 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=126 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=127 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=128 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;
-- update ci_tweet set adminid=129 where f_catalog_id=1 and s_catalog_id=101 and adminid is NULL and type=1 and is_del=0 and new_tags is null order by tid desc LIMIT 14;


-- 能力模型素材 执行语句  f_catalog_id (1,4,5)
-- update myb_capacitymodel_material set adminid=$userid where f_catalog_id=$f_catalog_id and s_catalog_id=$s_catalog_id and `status`=0 and  adminid is null order by  materialid desc limit $number;

-- update myb_capacitymodel_material set adminid=100 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=101 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=102 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=103 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=104 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=105 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=106 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=107 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=108 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=109 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=110 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=111 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=112 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=113 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=114 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=115 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=116 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=117 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=118 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=119 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=120 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=121 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=122 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=123 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=124 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=125 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=126 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=127 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=128 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=129 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;
-- update myb_capacitymodel_material set adminid=130 where f_catalog_id=1 and s_catalog_id=100 and adminid is NULL and status=0 and new_tags is null order by materialid desc LIMIT 123;


-- 到出所以需要上线数据
-- mysqldump -h 192.168.1.14 -uroot -pmyb123 myb_tag >D:/myb_tag.sql






-- 打标签项目需要导出的数据表
-- ci_resource
-- ci_user_detail
-- 
-- 
-- ci_tweet
-- select * from ci_tweet where type=1 and is_del=0;
-- 
-- myb_capacitymodel_material
-- 
-- select *from myb_capacitymodel_material where status=0;
-- 
-- 
-- myb_mis_user_vest
-- myb_mis_user
-- myb_mis_role_resource
-- myb_mis_role
-- myb_mis_resource
-- ci_rbac_user