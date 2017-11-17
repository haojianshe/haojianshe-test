<?php

namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use mis\service\LectureService;
use mis\service\NewsService;
use mis\service\NewsDataService;
use mis\service\ResourceService;
use common\service\DictdataService;
use common\models\myb\Course;

/**
 * 精讲添加和修改页面
 */
class EditAction extends MBaseAction {

    public $resource_id = 'operation_lecture';

    public function run() {
        $request = Yii::$app->request;
        $type = $request->get('type');
        $province = DictdataService::getProvince();
        $profession = DictdataService::getProfession();
        if (!$request->isPost) {
            //处理get请求
            $ret = $this->getHandle();
            $ret['type'] = $type;
            $ret['province'] = $province;
            $ret['profession'] = $profession;
        } else {
            //处理post请求
            $ret = $this->postHandle();
            $ret['type'] = $type;
            $ret['province'] = $province;
            $ret['profession'] = $profession;
        }

        return $this->controller->render('edit', $ret);
    }

    /**
     * 处理get访问的情况
     */
    private function getHandle() {
        $request = Yii::$app->request;
        $msg = '';

        //判断参数
        $newsid = $request->get('newsid');
        $type = $request->get('type');
        if ($newsid) {
            //编辑
            if (!is_numeric($newsid)) {
                die('非法输入');
            }
        } else {
            //新添加
            $newsid = 0;
        }
        $ret = $this->getRetModel($newsid);
        $ret['msg'] = $msg;
        return $ret;
    }

    /**
     * 处理post访问的情况
     */
    private function postHandle() {
        $redis = Yii::$app->cache;
        $request = Yii::$app->request;
        $msg = '';
        $usermodel = \Yii::$app->user->getIdentity();
        //先获取model
        if ($request->post('isedit') == 1) {
            $newsid = $request->post('LectureService')['newsid'];
        } else {
            $newsid = 0;
        }

        $courseids = @$request->post('LectureService')['courseids'];
        if (!empty($courseids)) {
            $courseArr = explode(',', $courseids);
            if (count($courseArr) != 2) {
                die('精彩课程必须为两个');
            }
        }
        //从公共方法先获取model并解析用户输入
        $ret = $this->getRetModel($newsid);
        $lecturemodel = $ret['lecturemodel'];
        $newsmodel = $ret['newsmodel'];
        $newsdatamodel = $ret['newsdatamodel'];
        //获取用户输入的界面
        $lecturemodel->load($request->post());
        $newsmodel->load($request->post());
        $newsdatamodel->load($request->post());
        //将类型赋值回model里，出错时能够保存当前输入
        $ret['lecturemodel'] = $lecturemodel;
        $ret['newsmodel'] = $newsmodel;
        $ret['newsdatamodel'] = $newsdatamodel;
        //检查缩略图，必须1或者3张
        $thumb = $request->post('thumb');
        if ($thumb[0] == '') {
            die('缩略图错误');
        }
        if (($thumb[1] == '' && $thumb[2] <> '') || ($thumb[2] == '' && $thumb[1] <> '')) {
            die('缩略图错误');
        }
        $newsmodel->thumb = (string) $this->thumbHandle($thumb, $ret['thumbs']);
        //处理主分类和子分类
        if ($lecturemodel->lecture_level1 == 0) {
            $lecturemodel->lecture_level1 = null;
        }
        if ($lecturemodel->lecture_level2 == 0) {
            $lecturemodel->lecture_level2 = null;
        }
        //课程id
        if ($request->post('radio') == 1) {
            $lecturemodel->courseids = NULL;
        } else if ($request->post('radio') == 2) {
            if ($request->post('courseid_one')) {
                $lecturemodel->courseids = $request->post('courseid_one') . ',' . $request->post('courseid_two');
            }
        }

        //定时发布
        if ($lecturemodel->publishtime == null || $lecturemodel->publishtime === '') {
            $lecturemodel->publishtime = time();
        } else {
            $lecturemodel->publishtime = strtotime($lecturemodel->publishtime);
        }

        //保存投放区域 和 针对身份
        $proviceids = $request->post('proviceids');
        $professionids = $request->post('professionids');
        if ($proviceids) {
            if (count($proviceids) >= 1 && count($proviceids) < 36) {
                $lecturemodel->proviceids = implode(',', $proviceids);
            }
//            else {
//                $lecturemodel->proviceids = '100';
//            }
            if (count($professionids) < 10) {
                $lecturemodel->professionids = implode(',', $professionids);
            }
//            else {
//                $lecturemodel->professionids = '100';
//            }
        }
        //保存
        if ($request->post('isedit') == 1) {
            //编辑保存
            if (!$lecturemodel->validate() || !$newsmodel->validate() || !$newsdatamodel->validate()) {
                $ret['msg'] = '输入错误，请检查输入项';
            } else {
                //保存
                $newsmodel->utime = time();
                if ($lecturemodel->save() && $newsmodel->save() && $newsdatamodel->save()) {
                    if ($request->post('lecture_level_name') != $request->post('LectureService')['lecture_level1']) {
                        $redis->delete('all_lecture_list_' . $request->post('lecture_level_name'));
                    }
                    $ret['isclose'] = true;
                    $ret['msg'] = '保存成功';
                } else {
                    $ret['msg'] = '保存失败';
                }
            }
        } else {
            //新增 操作员
            $newsmodel->username = $usermodel->mis_realname;
            $newsmodel->ctime = time();
            $newsmodel->utime = $newsmodel->ctime;
            //先保存news表获取id
            if ($newsmodel->validate() && $newsmodel->save()) {
                $lecturemodel->newsid = $newsmodel->newsid;
                $lecturemodel->status = 2;
                $newsdatamodel->newsid = $newsmodel->newsid;
                if ($_POST['type'] == 2) {
                    $newsdatamodel->content = '0';
                    $lecturemodel->newstype = 2;
                }
                if ($newsdatamodel->save(true) && $lecturemodel->save(true)) {
                    $ret['isclose'] = true;
                    $ret['msg'] = '保存成功';
                } else {
                    $ret['msg'] = '保存失败';
                }
            } else {
                $ret['msg'] = '保存失败';
            }
        }
        return $ret;
    }

    /**
     * 处理缩略图,如果改变则更新resource库
     * 新增则insert数据库，否则update
     */
    private function thumbHandle($thumb, $thumbold) {
        $ret = '';
        //处理1图的情况
        foreach ($thumb as $k => $v) {
            //缩略图不为空
            if ($v != '') {
                if ($v != $thumbold[$k]['img']) {
                    //新的缩略图
                    if ($thumbold[$k]['rid'] == '') {
                        $model = new ResourceService();
                    } else {
                        $model = $thumbold[$k]['model'];
                    }
                    $model->img = $thumb[$k];
                    $model->save();
                    //记录rid
                    $rid = $model->rid;
                } else {
                    $rid = $thumbold[$k]['rid'];
                }
                if ($ret == '') {
                    $ret = $rid;
                } else {
                    $ret .= ',' . $rid;
                }
            }
        }
        return $ret;
    }

    /**
     * 根据newsid获取所有到的精讲model
     * newsid为0代表新建 不为0则从数据库取数据
     * 将主类型和子类型也取出，用于页面下拉框展示
     * 返回精讲编辑页的model
     */
    private function getRetModel($newsid) {
        //获取主类型,添加未选择选项
        $maintypemodel = DictdataService::getLectureMainType();
        array_unshift($maintypemodel, ['maintypeid' => 0, 'maintypename' => '选择主类型']);
        $ret['maintypemodel'] = $maintypemodel;
        //分类型
        $subtypemodel = [];
        if ($newsid == 0) {
            //获取精讲详细信息
            $lecturemodel = new LectureService();
            //默认发布时间是当前
            $lecturemodel->publishtime = time();
            $ret['lecturemodel'] = $lecturemodel;
            $ret['newsmodel'] = new NewsService();
            $ret['newsdatamodel'] = new NewsDataService();
        } else {
            $lecturemodel = LectureService::findOne(['newsid' => $newsid]);

            //如果存在推荐课程，分开推荐课程
            if ($lecturemodel->courseids) {
                $courseid = explode(',', $lecturemodel->courseids);
                $courseData = Course::find()->select(['title', 'courseid'])->where(['in', 'courseid', $courseid])->asArray()->all();
                $ret['course'] = $courseData;
            }
            $ret['lecturemodel'] = $lecturemodel;
            $ret['newsmodel'] = NewsService::findOne(['newsid' => $newsid]);
            $ret['newsdatamodel'] = NewsDataService::findOne([['newsid' => $newsid]]);
            //根据主类型选择分类型
            if ($lecturemodel->lecture_level1) {
                $subtypemodel = DictdataService::getLectureSubType($lecturemodel->lecture_level1);
            }
        }
        //缩略图
        $ret['thumbs'] = $this->getThumbs($ret['newsmodel']);
        if (!empty($subtypemodel)) {
            array_unshift($subtypemodel, ['subtypeid' => 0, 'subtypename' => '选择分类型']);
            $ret['subtypemodel'] = $subtypemodel;
        }
        return $ret;
    }

    /**
     * 获取缩略图
     * @param unknown $newsmodel
     */
    private function getThumbs($newsmodel) {
        if ($newsmodel->thumb) {
            //从数据库获取thumb信息
            $arrrid = explode(',', $newsmodel->thumb);
            for ($i = 0; $i < 3; $i++) {
                if (isset($arrrid[$i])) {
                    $rmodel = ResourceService::findOne(['rid' => $arrrid[$i]]);
                    $thumbs[] = ['rid' => $rmodel->rid, 'img' => $rmodel->img, 'model' => $rmodel];
                } else {
                    $thumbs[] = ['rid' => '', 'img' => ''];
                }
            }
        } else {
            for ($i = 0; $i < 3; $i++) {
                $thumbs[] = ['rid' => '', 'img' => ''];
            }
        }
        return $thumbs;
    }

}
