<?php
namespace mis\controllers\stat;

use Yii;
use yii\base\Action;
use common\models\myb\User;

use mis\components\MBaseAction;
/**
 * 判断是否用户是否统一设备注册
 */
class UserTokensAction extends MBaseAction{
	public  function run(){
			$request = Yii::$app->request;
	        $umobile = $request->get('umobile');
	        //去除回车换行
			$umobile = str_replace(PHP_EOL, '', $umobile);   

			$ret=User::find()->alias("a")->select("a.umobile,b.xg_device_token,ios_device_token")->leftJoin("ci_user_push b","b.uid=a.id")->where(['in',"a.umobile",explode(",", $umobile)])->andWhere(["a.register_status"=>0])->asArray()->all();
			$html='<table>';

			$html.="<tr>";
			$html.="<td width='120px;'>手机号<td>";
			$html.="<td>设备号<td>";
			$html.="</tr>";

			foreach ($ret as $key => $value) {
				$html.="<tr>";
				$html.="<td>".$value['umobile']."<td>";
				$html.="<td>".$value['xg_device_token']."<td>";
				$html.="</tr>";

			}

			$html.="<tr>";
			$html.="<td colspan='2'>总条数：".count($ret)."<td>";
			$html.="</tr>";

			$html.='</table>';
			die($html);
		
		}
}