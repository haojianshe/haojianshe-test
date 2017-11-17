1.  复制代码到新的目录
    删除 m.meiyuanbang.com  api.meiyuanbang.com  www.meiyuanbang.com 保留mis.meiyuanbang.com
    删除 mis.meiyuanbang.com 
      controller 除index(注册登陆)  tweet（帖子管理） metail（能力模型素材管理）外 controller
      models 内文件
      views 除index(注册登陆)  tweet（帖子管理） metail（能力模型素材管理）外 文件

2. 新增svn版本管理库 提交代码

3. 新建数据库 myb_copy

      复制原myb中的 
      -- 用户表
      myb_mis_user, 
      myb_mis_role, 
      myb_mis_resource,
      myb_mis_role_resource
      -- 素材表
      ci_tweet, 
      -- (能力模型表)
      myb_capacitymodel_material

4. 更改配置文件
    mis.meiyuanbang.com/config 数据库配置

5.增加nginx域名配置
    增加 mis2.meiyuanbang.com 到nginx 配置文件

6.增加用户角色 
    --sql  全为管理员用户  分为更改标签权限 跟 普通打标签权限

-- 新建标签分组表
CREATE TABLE `myb_tag_group` (
  `taggroupid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `tag_group_name` varchar(50) DEFAULT NULL COMMENT '标签类型名称',
  `tag_group_type` int(11) NOT NULL DEFAULT 1 COMMENT '标签类型 1单选 2多选',
  `f_catalog_id` smallint(6) DEFAULT NULL COMMENT '一级分类id',
  `s_catalog_id` smallint(6) DEFAULT NULL COMMENT '二级分类id',
  `status` tinyint(1) unsigned NOT NULL  COMMENT '1/2 正常/删除',
  `ctime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`taggroupid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='标签类型表';

-- 标签表
CREATE TABLE `myb_tags` (
  `tagid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `taggroupid` int(11) unsigned NOT NULL ,
  `tag_name` varchar(50) DEFAULT NULL COMMENT '标签名称',
  `status` tinyint(1) unsigned NOT NULL  COMMENT '1/2 正常/删除',
  `ctime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`tagid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='标签库';


