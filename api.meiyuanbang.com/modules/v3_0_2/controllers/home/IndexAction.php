<?php
namespace api\modules\v3_0_2\controllers\home;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;

use api\service\LiveService;
use api\service\CourseService;
use api\service\LectureService;
use api\service\LessonService;
use api\service\PublishingBookService;
use api\service\LiveRecommendService;
use api\service\MaterialSubjectService;
use api\service\UserDetailService;
/**
 * 首页信息
 * @author ihziluoh
 *
 */
class IndexAction extends ApiBaseAction{

   public  function run(){
        $professionid=$this->requestParam('professionid')?$this->requestParam('professionid'):0;
        //0=>高三,1=>高二,2=>高一,3=>初中,4=>大学,5=>老师,6=>其他,7=>小学,
        //A类（适用于小学、初中）
        //B类（适用于高一、高二、高三、大学、老师、其他、红笔、黄V、出版社）
      
        $uid=$this->_uid;
        //普通用户
        $role=1;
        if(intval($uid)>0){
            $userinfo=UserDetailService::getByUid($uid);
            if($userinfo['featureflag']==1){
                $role=2;
                //红笔角色
            }
            if($userinfo['ukind']==1 && $userinfo['ukind_verify']==1){
                $role=3;
                //黄V
            }
            if($userinfo['role_type']==2){
                $role=4;
                //出版社
            }
        }
        
        //获取推荐课程 精讲等
        $data=$this->getListDataRedis($professionid,$role);

        //后台推荐直播
        $recliveids=LiveRecommendService::getLiveRecommendIds();
        $data->live_recommend=LiveService::getListDetail($recliveids);

        //正在进行的直播
        $onlineliveids=LiveService::getOnlineLiveList();
        $data->live_online=LiveService::getListDetail($onlineliveids);        
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
    /**
     * 缓存获取推荐列表
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    private function getListDataRedis($professionid,$role){
        $rediskey="home_list_type_".$professionid.'_role'.$role;
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $list=$redis->get($rediskey);
        if (empty($list)) {
           $list=json_encode($this->getListData($professionid,$role));
           if($list){
                $redis->set($rediskey,$list);
                $redis->expire($rediskey,3600);
           }
        }
        return json_decode($list);
    }
    /**
     * 得到推荐列表数据根据用户类型 A B
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    private function getListData($professionid,$role){
        $data['course']=[];
        $data['lecture']=[];
        $data['lesson']=[];
        $data['publishingbook']=[];
        $data['matreialsubject']=[];
        if($role>1){
            $search_param=$this->getSearchParamByUserRole($role);
        }else{
            $search_param=$this->getSearchParamByProfessionid($professionid);
        }
        //首页推荐直播(根据分类)
        //$recliveids=LiveService::getHomeLiveRecommendIds($search_param['live_search_catalog']);
        //$data['live_recommend']=LiveService::getListDetail($recliveids);


     

        $data['course']=CourseService::getCourseByCatalogRand($search_param['course_search_catalog']);
        $data['lecture']=LectureService::getLectureByCatalogRand($search_param['lecture_search_catalog'],$search_param['lecture_limit']);
        $data['lesson']=LessonService::getLessonByCatalogRand($search_param['lesson_search_catalog']);
        $data['publishingbook']=PublishingBookService::getBooksByCatalogRand($search_param['book_search_catalog']);
        $data['matreialsubject']=MaterialSubjectService::getHomeRecommend($search_param['matreialsubject_search_catalog']);
        return $data;
    }
    /**
     * 角色用户搜索条件
     * @param  integer $roleid [description]
     * @return [type]          [description]
     */
    private function getSearchParamByUserRole($roleid=0){

        //普通用户 $role=1;
        //红笔角色 $role=2;
        //黄V $role=3;
        //出版社 $role=4;
        //直播课
        $data['live_search_catalog']=[
                    [
                        //素描-组合静物
                        'f_catalog_id'=>4,
                        's_catalog_id'=>126,
                        'limit'=>1
                    ],
                    [   //色彩-组合静物
                        'f_catalog_id'=>1,
                        's_catalog_id'=>100,
                        'limit'=>1
                    ]
                 ];


        $data['lecture_limit']=1;
        $data['lecture_search_catalog']=[];

        $data['matreialsubject_search_catalog']=[
                                                      [   
                                                            'f_catalog_id'=>2,
                                                            'limit'=>3
                                                        ],
                                                        [   
                                                            'f_catalog_id'=>6,
                                                            'limit'=>4
                                                        ],
                                                        [   
                                                            'f_catalog_id'=>3,
                                                            'limit'=>3
                                                        ],
                                                   
                                                ];
        $data['course_search_catalog']=[
                                    [
                                        //素描-头像
                                        'f_catalog_id'=>4,
                                        's_catalog_id'=>129,
                                        'limit'=>2
                                    ],
                                    [   //色彩-组合静物
                                        'f_catalog_id'=>1,
                                        's_catalog_id'=>100,
                                        'limit'=>1
                                    ],
                                    [   //色彩-场景
                                        'f_catalog_id'=>1,
                                        's_catalog_id'=>1003,
                                        'limit'=>1
                                    ],
                                    [   //色彩-风景
                                        'f_catalog_id'=>1,
                                        's_catalog_id'=>103,
                                        'limit'=>1
                                    ],
                                    [   //速写-人物速写
                                        'f_catalog_id'=>5,
                                        's_catalog_id'=>133,
                                        'limit'=>1
                                    ],
                                    [   //速写-动态快写
                                        'f_catalog_id'=>5,
                                        's_catalog_id'=>135,
                                        'limit'=>1
                                    ],
                                    [   //速写-场景速写
                                        'f_catalog_id'=>5,
                                        's_catalog_id'=>137,
                                        'limit'=>1
                                    ]
                             ];        
        $data['lesson_search_catalog']=[
                                    [
                                        //素描-头像
                                        'f_catalog_id'=>4,
                                        's_catalog_id'=>129,
                                        'limit'=>1
                                    ],
                                    [   //素描-组合静物
                                        'f_catalog_id'=>4,
                                        's_catalog_id'=>126,
                                        'limit'=>1
                                    ]
                                    ,
                                    [   //色彩-组合静物
                                        'f_catalog_id'=>1,
                                        's_catalog_id'=>100,
                                        'limit'=>1
                                    ],
                                    [   //速写-人物速写
                                        'f_catalog_id'=>5,
                                        's_catalog_id'=>133,
                                        'limit'=>1
                                    ]
                             ];

        $data['book_search_catalog']=[
                                [
                                    //素描-头像
                                    'f_catalog_id'=>4,
                                    's_catalog_id'=>129,
                                    'limit'=>1
                                ],
                                [   //色彩-静物
                                    'f_catalog_id'=>1,
                                    's_catalog_id'=>100,
                                    'limit'=>1
                                ],
                                [   //速写-人物速写
                                    'f_catalog_id'=>5,
                                    's_catalog_id'=>133,
                                    'limit'=>1
                                ]
                             ];
  
        return $data;
    }
    private function getSearchCourseByProfessionid($professionid){
        $data=[];
        switch ($professionid) {
            //0=>高三,1=>高二,2=>高一,3=>初中,4=>大学,5=>老师,6=>其他,7=>小学,
            case 3:
               $data=[
                        [
                            //素描-单体静物
                            'f_catalog_id'=>4,
                            's_catalog_id'=>125,
                            'limit'=>1
                        ],[
                            //素描-组合静物
                            'f_catalog_id'=>4,
                            's_catalog_id'=>126,
                            'limit'=>2
                        ]
                        ,
                        [   //素描-人物局部
                            'f_catalog_id'=>4,
                            's_catalog_id'=>4002,
                            'limit'=>1
                        ],
                        [   //色彩-静物单体
                            'f_catalog_id'=>1,
                            's_catalog_id'=>101,
                            'limit'=>1
                        ],
                        [   //色彩-组合静物
                            'f_catalog_id'=>1,
                            's_catalog_id'=>100,
                            'limit'=>2
                        ],
                        
                        [   //速写-人物速写
                            'f_catalog_id'=>5,
                            's_catalog_id'=>133,
                            'limit'=>1
                        ]
                     ];
                   
                break;
            case 7:
                $data=[
                        [
                            //素描-单体静物
                            'f_catalog_id'=>4,
                            's_catalog_id'=>125,
                            'limit'=>2
                        ],[
                            //素描-组合静物
                            'f_catalog_id'=>4,
                            's_catalog_id'=>126,
                            'limit'=>1
                        ]
                        ,
                        [   //素描-人物局部
                            'f_catalog_id'=>4,
                            's_catalog_id'=>4002,
                            'limit'=>1
                        ],
                        [   //色彩-静物单体
                            'f_catalog_id'=>1,
                            's_catalog_id'=>101,
                            'limit'=>2
                        ],
                        [   //色彩-组合静物
                            'f_catalog_id'=>1,
                            's_catalog_id'=>100,
                            'limit'=>1
                        ],
                        
                        [   //速写-人物速写
                            'f_catalog_id'=>5,
                            's_catalog_id'=>133,
                            'limit'=>1
                        ]
                     ];
                   
                break;
            case 2://2=>高一
                $data=[
                                            [   //素描-组合静物
                                                'f_catalog_id'=>4,
                                                's_catalog_id'=>126,
                                                'limit'=>2
                                            ]
                                            ,
                                            [
                                                //素描-头像
                                                'f_catalog_id'=>4,
                                                's_catalog_id'=>129,
                                                'limit'=>2
                                            ],
                                            [   //色彩-静物单体
                                                'f_catalog_id'=>1,
                                                's_catalog_id'=>101,
                                                'limit'=>2
                                            ],
                                            
                                            [   //色彩-组合静物
                                                'f_catalog_id'=>1,
                                                's_catalog_id'=>100,
                                                'limit'=>2
                                            ]
                        ];
                break;
            case 1://1=>高二
                $data=[
                        [   //素描-组合静物
                            'f_catalog_id'=>4,
                            's_catalog_id'=>126,
                            'limit'=>2
                        ]
                        ,
                        [
                            //素描-头像
                            'f_catalog_id'=>4,
                            's_catalog_id'=>129,
                            'limit'=>2
                        ],
                        
                        [   //色彩-组合静物
                            'f_catalog_id'=>1,
                            's_catalog_id'=>100,
                            'limit'=>2
                        ],
                        [   //速写-人物速写
                            'f_catalog_id'=>5,
                            's_catalog_id'=>133,
                            'limit'=>2
                        ]
                 ];
               
                break;
            case 0://0=>高三
                $data=[
                            [
                                //素描-头像
                                'f_catalog_id'=>4,
                                's_catalog_id'=>129,
                                'limit'=>2
                            ],
                            
                            [   //色彩-组合静物
                                'f_catalog_id'=>1,
                                's_catalog_id'=>100,
                                'limit'=>2
                            ],
                            [   //速写-人物速写
                                'f_catalog_id'=>5,
                                's_catalog_id'=>133,
                                'limit'=>2
                            ],
                            [   //速写-场景速写
                                'f_catalog_id'=>5,
                                's_catalog_id'=>137,
                                'limit'=>2
                            ]
                     ];
              
                break;
            //0=>高三,1=>高二,2=>高一,3=>初中,4=>大学,5=>老师,6=>其他,7=>小学,
            case 4:
            case 5:
            case 6:
                $data=[
                        [
                            //素描-头像
                            'f_catalog_id'=>4,
                            's_catalog_id'=>129,
                            'limit'=>2
                        ],
                        
                        [   //色彩-组合静物
                            'f_catalog_id'=>1,
                            's_catalog_id'=>100,
                            'limit'=>2
                        ],
                        [   //色彩-风景
                            'f_catalog_id'=>1,
                            's_catalog_id'=>103,
                            'limit'=>2
                        ],
                        [   //速写-人物速写
                            'f_catalog_id'=>5,
                            's_catalog_id'=>133,
                            'limit'=>1
                        ],
                        [   //速写-场景速写
                            'f_catalog_id'=>5,
                            's_catalog_id'=>137,
                            'limit'=>1
                        ]
                     ];
            break;
            default:
                break;
        }
        return $data;
    }
    private function getSearchLessonByProfessionid($professionid){
        $data=[];
        switch ($professionid) {
            //0=>高三,1=>高二,2=>高一,3=>初中,4=>大学,5=>老师,6=>其他,7=>小学,
            case 7://7=>小学,
                $data=[
                                           
                        [   //素描-组合几何
                            'f_catalog_id'=>4,
                            's_catalog_id'=>124,
                            'limit'=>1
                        ]
                        ,
                        [   //色彩-单体静物
                            'f_catalog_id'=>1,
                            's_catalog_id'=>101,
                            'limit'=>1
                        ]
                 ];
                break;
            case 3:
            case 2://2=>高一
                $data=[
                                           
                        [   //素描-组合静物
                            'f_catalog_id'=>4,
                            's_catalog_id'=>126,
                            'limit'=>1
                        ]
                        ,
                        [   //色彩-组合静物
                            'f_catalog_id'=>1,
                            's_catalog_id'=>100,
                            'limit'=>1
                        ]
                 ];
                break;
           
            case 1://1=>高二
            case 4:
            case 5:
            case 6:
            case 0:
                $data=[
                            [
                                //素描-头像
                                'f_catalog_id'=>4,
                                's_catalog_id'=>129,
                                'limit'=>1
                            ],
                            [   //色彩-组合静物
                                'f_catalog_id'=>1,
                                's_catalog_id'=>100,
                                'limit'=>1
                            ]
                            
                     ];
                break;
            default:
                break;
        }
        return $data;

    }
    private function getSearchBookByProfessionid($professionid){
        $data=[];
        switch ($professionid) {
            //0=>高三,1=>高二,2=>高一,3=>初中,4=>大学,5=>老师,6=>其他,7=>小学,
            case 3:
            case 7:
                     $data=[
                                [
                                    //素描-单体静物
                                    'f_catalog_id'=>4,
                                    's_catalog_id'=>125,
                                    'limit'=>1
                                ],
                                 [
                                    //素描-组合静物
                                    'f_catalog_id'=>4,
                                    's_catalog_id'=>126,
                                    'limit'=>1
                                ],
                                [   //色彩-静物
                                    'f_catalog_id'=>1,
                                    's_catalog_id'=>100,
                                    'limit'=>1
                                ]
                             ];
                break;
            case 2://2=>高一
                  $data=[
                                
                                 [
                                    //素描-组合静物
                                    'f_catalog_id'=>4,
                                    's_catalog_id'=>126,
                                    'limit'=>1
                                ],
                                [   //色彩-静物
                                    'f_catalog_id'=>1,
                                    's_catalog_id'=>100,
                                    'limit'=>1
                                ],[
                                    //素描-头像
                                    'f_catalog_id'=>4,
                                    's_catalog_id'=>129,
                                    'limit'=>1
                                ]
                             ];
                break;
            case 1://1=>高二
            case 0:
            case 4:
            case 5:
            case 6:
                $data=[
                                [
                                    //素描-头像
                                    'f_catalog_id'=>4,
                                    's_catalog_id'=>129,
                                    'limit'=>1
                                ],
                                [   //色彩-静物
                                    'f_catalog_id'=>1,
                                    's_catalog_id'=>100,
                                    'limit'=>1
                                ],
                                 [
                                    //速写-人物速写
                                    'f_catalog_id'=>5,
                                    's_catalog_id'=>133,
                                    'limit'=>1
                                ]
                             ];
                break;
            default:
                break;
        }
        return $data;

    }
    private function getSearchLiveByProfessionid($professionid){
        $data=[];
        switch ($professionid) {
            //0=>高三,1=>高二,2=>高一,3=>初中,4=>大学,5=>老师,6=>其他,7=>小学,
            case 7:
                     $data=[
                        [   //素描-单体静物
                            'f_catalog_id'=>4,
                            's_catalog_id'=>125,
                            'limit'=>1
                        ],
                        [
                            //素描-组合静物
                            'f_catalog_id'=>4,
                            's_catalog_id'=>126,
                            'limit'=>1
                        ]
                     ];
                break;
            case 0:
            case 1://1=>高二
            case 2://2=>高一
            case 3://2=>高一
            case 4:
            case 5:
            case 6:
                $data=[
                        [
                            //素描-组合静物
                            'f_catalog_id'=>4,
                            's_catalog_id'=>126,
                            'limit'=>1
                        ],
                        [   //色彩-组合静物
                            'f_catalog_id'=>1,
                            's_catalog_id'=>100,
                            'limit'=>1
                        ]
                        
                     ];
                break;
            default:
                break;
        }
        return $data;
    }
    /**
     * 用户群推荐搜索条件
     * @return [type] [description]
     */
    private function getSearchParamByProfessionid($professionid){
            //直播课
            $data['live_search_catalog']=$this->getSearchLiveByProfessionid($professionid);
            $data['matreialsubject_search_catalog']=[
                                                        [   
                                                            'f_catalog_id'=>2,
                                                            'limit'=>3
                                                        ],
                                                        [   
                                                            'f_catalog_id'=>6,
                                                            'limit'=>4
                                                        ],
                                                        [   
                                                            'f_catalog_id'=>3,
                                                            'limit'=>3
                                                        ],
                                                   
                                                ];
            $data['lecture_limit']=1;
            $data['lecture_search_catalog']=[];
            $data['course_search_catalog']=$this->getSearchCourseByProfessionid($professionid);
            $data['lesson_search_catalog']=$this->getSearchLessonByProfessionid($professionid);
            $data['book_search_catalog']=$this->getSearchBookByProfessionid($professionid);
        
        return $data;
        
    }
   
}