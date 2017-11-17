<?php

namespace mis\controllers\correct;

use Yii;
use yii\base\Action;
use mis\service\TweetService;
use api\service\CorrectService;
use mis\service\ResourceService;
use mis\components\MBaseAction;
use common\service\CommonFuncService;
use common\service\DictdataService;
use common\service\dict\CorrectRefuseReasonService;
use common\service\dict\CapacityModelDictDataService;

/**
 * 后台批改列表页
 * 
 */
class IndexAction extends MBaseAction {

    public $resource_id = 'operation_correct';

    public function run() {
        $request = Yii::$app->request;

        //用户名或批改老师
        $sname = trim($request->get('sname'));
        //1代表用户名 2代表批改老师 //3代表手机
        $subjecttype = $request->get('subjecttype');
        $s_catalog_id = $request->get('s_catalog_id');
        $f_catalog_id = $request->get('f_catalog_id');
        $status = $request->get('status');
        //开始时间
        $start_time = trim($request->get('start_time'));
        //结束时间
        $end_time = trim($request->get('end_time'));
        /* var_dump($status);exit; */
        $status_where = '';

        //删除  打回  转作品
        switch (intval($status)) {
            case 1:
                $status_where = 'myb_correct.status=0';
                break;
            case 2:
                $status_where = 'myb_correct.status=1';
                break;
            case 3:
                $status_where = '(myb_correct.status=2 or myb_correct.status=3)';
                break;
            default:
                $status_where = '( 1=1 )';
                break;
        }

        //创建时间默认赋值
        if (empty($start_time)) {
            $start_time = date("Y-m-d 00:00", strtotime("-7 day"));
        }
        if (empty($end_time)) {
            $end_time = date('Y-m-d 00:00', strtotime("+1 day"));
        }

        if ($subjecttype == 2) {
            if (isset($sname) && !empty($sname)) {
                $where = ' cudOne.sname like "%' . $sname . '%" and ' . $status_where;
            } else {
                $where = $status_where;
            }

            if ($start_time) {
                $where .=' and myb_correct.ctime>=' . strtotime($start_time) . ' and myb_correct.ctime <=' . strtotime($end_time);
            }
            #主分类二级分类搜索
            if ($f_catalog_id) {
                if ($s_catalog_id) {
                    $where .=' and ci_tweet.f_catalog_id=' . $f_catalog_id . ' and ci_tweet.s_catalog_id=' . $s_catalog_id;
                } else {
                    $where .=' and ci_tweet.f_catalog_id=' . $f_catalog_id;
                }
            }
            //分页获取帖子列表
            $data = TweetService::getTweetByPageTeacher($where);
            //用户
        } else if ($subjecttype == 1 || $subjecttype == 3 || $subjecttype == 0) { # 
            if (isset($sname) && !empty($sname)) {
                if ($subjecttype != 3) {
                    $where = ' sname like "%' . $sname . '%" and ' . $status_where;
                } else {
                      $where = $status_where;
                }
            } else {
                $where = $status_where;
            }
            if ($start_time) {
                    $where .=' and ci_tweet.ctime>=' . strtotime($start_time) . ' and ci_tweet.ctime <=' . strtotime($end_time);
              
            }
            #主分类二级分类搜索
            if ($f_catalog_id) {
                if ($s_catalog_id) {
                    $where .=' and ci_tweet.f_catalog_id=' . $f_catalog_id . ' and ci_tweet.s_catalog_id=' . $s_catalog_id;
                } else {
                    $where .=' and ci_tweet.f_catalog_id=' . $f_catalog_id;
                }
            }

            # echo $where;
            //分页获取帖子列表
            $data = TweetService::getTweetByPage($where, $subjecttype, $sname);
        }
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
            $data['models'][$key]['correct_info'] = CorrectService::getListDetailInfo($value['correctid']);
            if ($data['models'][$key]['correct_info']['refuse_reasonid']) {
                $data['models'][$key]['correct_info']['reasondesc'] = CorrectRefuseReasonService::getModelById($data['models'][$key]['correct_info']['refuse_reasonid'])['reasondesc'];
            } else {
                $data['models'][$key]['correct_info']['reasondesc'] = '';
            }
            //添加显示打分项的内容
            $data['models'][$key]['correct_info']['scoredetail'] ='';
            if($value['score'] && $value['f_catalog_id']){
            	$data['models'][$key]['correct_info']['scoredetail'] = $this->getScoreDetail($value['markdetail'], $value['f_catalog_id'], $value['score']);
            }            
        }
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
        $data['sname'] = $sname;
        $data['subjecttype'] = $subjecttype;
        $data['status'] = $status;
        $data['s_catalog_id'] = $s_catalog_id;
        $data['f_catalog_id'] = $f_catalog_id;
        return $this->controller->render('index', $data);
    }
    
    /**
     * 根据打分详情拼mis前段显示的字符串
     * @param unknown $scoremark
     */
    private function getScoreDetail($scoremark,$maintypeid,$score){
    	$ret = '';
    	$max = 0;
    	$min = 0;
    	//对应类型下的所有打分项
    	$allitems=CapacityModelDictDataService::getCorrectScoreItemByMainId($maintypeid);
    	$scoreModel = json_decode($scoremark,true);
    	foreach ($scoreModel as $k=>$v){
    		$item = CapacityModelDictDataService::getCorrectScoreItemByItemid($v['itemid'], $allitems);
    		$ret .= $item['itemname'].':'.$v['score'].'  ';
    		//取最大最小的打分值
    		if($max==0 && $min==0){
    			$max = $v['score'];
    			$min = $v['score'];
    		}
    		else{
    			if($v['score']>$max){
    				$max = $v['score'];
    			}
    			if($v['score']<$min){
    				$min = $v['score'];
    			}
    		} 		
    	}
    	$ret .= '  总分:'.$score;
    	if($max-$min<4){
    		//疑似打分有问题项
			$ret = "<span style='color:red;'>" . $ret . '</span>';
    	}
    	return $ret;
    }
}
