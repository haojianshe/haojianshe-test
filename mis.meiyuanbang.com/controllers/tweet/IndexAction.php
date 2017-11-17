<?php

namespace mis\controllers\tweet;

use Yii;
use yii\base\Action;
use mis\service\TweetService;
use mis\service\ResourceService;
use mis\components\MBaseAction;
use common\service\CommonFuncService;
use common\service\DictdataService;

/**
 * 后台帖子列表页
 * 
 */
class IndexAction extends MBaseAction {

    public $resource_id = 'operation_tweet';

    public function run() {
        $request = Yii::$app->request;
        $sname = trim($request->get('sname'));
        $f_catalog_id = trim($request->get('f_catalog_id'));
        $s_catalog_id = trim($request->get('s_catalog_id'));
        $newstype = trim($request->get('newstype'));
        $start_time = trim($request->get('start_time'));
        $end_time = trim($request->get('end_time'));

        if (isset($sname) && !empty($sname)) {
            if ($newstype) {
                $where = ' sname like "%' . $sname . '%" and type =' . $newstype . ' and ';
            } else {
                $where = ' sname like "%' . $sname . '%" and type < 3 and ';
            }
        } else {
            if ($newstype) {
                $where = ' type =' . $newstype . ' and ';
            } else {
                $where = ' type<3 and ';
            }
        }
        //创建时间默认赋值
        if (empty($start_time)) {
            $start_time = date("Y-m-d 00:00", strtotime("-7 day"));
        }
        if (empty($end_time)) {
            $end_time = date('Y-m-d 00:00', strtotime("+1 day"));
        }
        $where .=' ci_tweet.ctime>=' . strtotime($start_time) . ' and ci_tweet.ctime <=' . strtotime($end_time);
        if ($f_catalog_id) {
            if ($s_catalog_id) {
                $where .=' and f_catalog_id=' . $f_catalog_id . ' and s_catalog_id=' . $s_catalog_id;
            } else {
                $where .=' and f_catalog_id=' . $f_catalog_id;
            }
        }
        //分页获取帖子列表
        $data = TweetService::getTweetMaterialByPage($where);
        #print_r($data);
        
        foreach ($data['models'] as $key => $value) {
            $data['models'][$key]['is_vest'] = in_array($value['uid'], DictdataService::getVestUser());
            $resources = ResourceService::findAll(['rid' => explode(',', $value['resource_id'])]);
            //为批改增加不同格式图片大小
            foreach ($resources as $k1 => $v1) {
                //为批改增加不同格式图片大小
                $arrtmp = json_decode($v1['img'], true);
                if (empty($arrtmp['l'])) {
                    $arrtmp['l'] = CommonFuncService::getPicByType($arrtmp['n'], 'l');
                }
                if (empty($arrtmp['s'])) {
                    $arrtmp['s'] = CommonFuncService::getPicByType($arrtmp['n'], 's');
                }
                if (empty($arrtmp['t'])) {
                    $arrtmp['t'] = CommonFuncService::getPicByType($arrtmp['n'], 't');
                }
                $resources[$k1]['img'] = json_encode($arrtmp);
            }
            //获取各种尺寸的图片，l s t,n是必须有的        	
            $data['models'][$key]['resources'] = $resources;
        }
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
        $data['sname'] = $sname;
        $data['newstype'] = $newstype;
        $data['f_catalog_id'] = $f_catalog_id;
        $data['s_catalog_id'] = $s_catalog_id;
        return $this->controller->render('index', $data);
    }

}
