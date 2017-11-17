<?php
namespace console\controllers;

use Yii;

/**
 * 资源表生成
 */
class ResourceController extends \yii\console\Controller
{
    public function actions()
    {
        return [
        	'updateresource' => [
        		'class' => 'console\controllers\resource\UpdateResourceAction',
        	]
        ];
    }
}

#/home/web/backcode/pushservice resource/updateresource &