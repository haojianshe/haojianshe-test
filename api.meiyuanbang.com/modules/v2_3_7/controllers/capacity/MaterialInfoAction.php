<?php
namespace api\modules\v2_3_7\controllers\capacity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CapacityModelMaterialService;
use api\service\CapacityModelMaterialZanService;
use api\service\CommentService;
use api\service\UserDetailService;
use common\service\CommonFuncService;

/**
 * 能力模型素材详情
 */
class MaterialInfoAction extends ApiBaseAction
{
    public function run()
    {   
        $materialid = $this->requestParam('materialid',true);
        //能力模型素材信息
        $data['matreial_info']=CapacityModelMaterialService::getMatreialDetail($materialid,$this->_uid);
        //点赞列表
        $data['praise']['user_list']=CapacityModelMaterialZanService::getZanUserList($materialid,NULL,$rn=10);
        $data['praise']['total_count']=CapacityModelMaterialZanService::getMaterialZanCount($materialid);

        //评论列表
        $content=CommentService::getListBySubject(9,$materialid, 10);
        $cmtcontent=CommentService::getCmtInfo($content,9);
        //处理时间
        foreach ($cmtcontent as $key => $value) {
           $cmtcontent[$key]['ctime']=CommonFuncService::format_time($value['ctime']);
        }
        $data['cmt']['cmt_list']=$cmtcontent;
        $data['cmt']['total_count']=CommentService::getCommentNum(9,$materialid);
        //分享信息
        $share['title']="帮星人".$data['matreial_info']['sname']."分布了一副作品";
        $share['desc']=$data['matreial_info']['content'];
        $share['img']=$data['matreial_info']['imgs']['l']['url'];
        $share['url']=Yii::$app->params['sharehost']."/capacitymaterial/material_detail?materialid=".$materialid;
        $data['share']=$share;
		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
