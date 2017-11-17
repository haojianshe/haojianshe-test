<?php

namespace mis\controllers\material;

use Yii;
use mis\components\MBaseAction;
use mis\service\TweetService;
use common\service\DictdataService;
use mis\service\ResourceService;
use common\service\CommonFuncService;

/**
 * 选择素材图片
 */
class SelAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    //public $resource_id = 'operation_material';

    public function run() {
        $request = Yii::$app->request;
        //获取参数
        $rids = $request->get('rids');
        $f_catalog = $request->get('f_catalog');
        $s_catalog = $request->get('s_catalog');
        $tags = $request->get('tags');
        //获取所选择的的标签
        $tags1 = $request->get('tags1');
        $content = $request->get('content');
        $url = Yii::$app->request->getUrl();
        //获取全部分类返回
        $config['imgmgr_level_1'] = DictdataService::getTweetMainType();
        $config['imgmgr_level_2'] = DictdataService::getTweetSubType();

        //通过分类获取分类id 用于搜索条件
        $s_catalog_id = '';
        $f_catalog_id = '';
        //添加一级和二级分类id
        if ($f_catalog) {
            $f_catalog_id = DictdataService::getTweetMainTypeIdByName($f_catalog);
            if ($s_catalog) {
                $s_catalog_id = DictdataService::getTweetSubTypeIdByName($f_catalog_id, $s_catalog);
            }
        }
        if ($tags1) {
            $tags1 = array_unique($tags1);
            unset($tags1[array_search("请选择", $tags1)]);
        }
        //获取素材列表
        $data = TweetService::findMaterialRid($f_catalog_id, $s_catalog_id, $tags, $content, $tags1);
        foreach ($data['models'] as $key => $value) {
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
        //返回已选择的图片resource_id集合
        $data['rids'] = explode(",", $rids);
        //返回当前搜索条件
        $data['model']["f_catalog"] = $f_catalog;
        $data['model']["s_catalog"] = $s_catalog;
        $data['model']["content"] = $content;
        $data['model']["tags"] = $tags;
        $data['model']["url"] = $url;
        //全部分类
        $data['catalog'] = $config;
        return $this->controller->render('search', $data);
    }

}
