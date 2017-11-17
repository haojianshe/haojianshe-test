
//-- 色彩 /10 m11-m20 439[每个人多少个]
//select count(*) from ci_tweet where type=1 and f_catalog_id=1;
//
//-- 设计  admin [每个人多少个]
//select count(*) from ci_tweet where type=1 and f_catalog_id=2;
//
//-- 照片 /5 m31-m35 623[每个人多少个]
//select count(*) from ci_tweet where type=1 and f_catalog_id=3;
//
//-- 素描  /10 m1-m10  360[每个人多少个]
//select count(*) from ci_tweet where type=1 and f_catalog_id=4;
//
//-- 速写 /10 m21-m30 608[每个人多少个]
//select count(*) from ci_tweet where type=1 and f_catalog_id=5;
//
//-- 创作 admin 380[每个人多少个]
//select count(*) from ci_tweet where type=1 and f_catalog_id=6;




//-- 色彩 /5 m36-m40 865[每个人多少个]
//select count(*) from myb_capacitymodel_material where `status`=0  and f_catalog_id=1;
//
//-- 素描   /5 m41-m45 913[每个人多少个]
//select count(*) from myb_capacitymodel_material where `status`=0 and f_catalog_id=4;
//
//-- 速写  /5 m46-m50 416[每个人多少个]
//select count(*) from myb_capacitymodel_material where `status`=0  and f_catalog_id=5;



//素材
//-- 色彩 /10 m11-m20 439[每个人多少个] 一共:4386
//-- 设计  admin 1520 [每个人多少个]  一共:1520
//-- 照片 /5 m31-m35 623[每个人多少个] 一共:3114
//-- 素描  /10 m1-m10  360[每个人多少个] 一共:3592
//-- 速写 /10 m21-m30 608[每个人多少个] 一共:1520
//-- 创作 admin 380[每个人多少个] 一共:380



//能力模型素材
//-- 色彩    /5 m36-m40  865 [每个人多少个] 一共:4322
//-- 素描    /5 m41-m45 913[每个人多少个] 一共:4563
//-- 速写    /5 m46-m50 416[每个人多少个] 一共:2079


/**
 * 分配规则
 * $param $startAdminId 开始的管理员id 
 * $param $endAdminId   结束的的管理员id 
 * $param $f   主分类id  
 * $param $nu   分配的条数  
 * $param $tab   要传递的表名称
 */
function setSql($startAdminId,$endAdminId,$f,$nu,$tab){
	if($tab=='ci_tweet'){
	 $where = " and type=1 and is_del=0 and new_tags is null order by tid desc ";
	}else{
	 $where = " and status=0 and new_tags is null order by materialid desc";
	}
  while($startAdminId<=$endAdminId){
  echo "update $tab set adminid=$startAdminId where f_catalog_id=$f  and adminid is NULL $where  LIMIT $nu;".'<br/>';
  $startAdminId++;
  }
}

setSql(100,126,1,175,'ci_tweet');
