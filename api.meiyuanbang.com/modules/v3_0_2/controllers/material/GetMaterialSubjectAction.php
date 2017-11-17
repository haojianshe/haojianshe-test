<?php

namespace api\modules\v3_0_2\controllers\material;

use Yii;
use api\components\ApiBaseAction;
use api\service\MaterialSubjectService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\CommonFuncService;


/**
 * 素材专题获取
 */
class GetMaterialSubjectAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
        //批改id
        $lastid = $this->requestParam('lastid');
        $f_catalog_id = $this->requestParam('f_catalog_id') ? $this->requestParam('f_catalog_id') : 0;
        $subject = MaterialSubjectService::getSubjectList($f_catalog_id, $lastid, $rn);
        
        if (!empty($subject)) {
            foreach ($subject as $mid) {
                $tmp = MaterialSubjectService::getMaterialDetail($mid);
                if ($tmp) {
                    #$tmp["picurl"] = json_decode($tmp["picurl"],1);
                    $tmp["picurl"] = [
                        'l'=>CommonFuncService::getPicByType((array)(json_decode($tmp["picurl"],1)['n']), 'l'),
                        't'=>CommonFuncService::getPicByType((array)(json_decode($tmp["picurl"],1)['n']), 't')
                    ];
                    $ret[] = $tmp;
                }
            }
        }else{
            $ret = (array)[];
        }
        if($ret){
            foreach($ret as $key=>$val){
                if($val['cmtcount']>99){
                    $ret[$key]['cmtcount']='99+';
                }
            }
        }
       $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, ['content' => $ret]);
    }

}
