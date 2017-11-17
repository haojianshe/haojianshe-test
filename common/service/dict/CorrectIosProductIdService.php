<?php
namespace common\service\dict;

use Yii;
use yii\base\Object;

/**
 * 付费批改Ios Productid
 */
class CorrectIosProductIdService extends Object
{  	
	/**
	 * ios 购买产品 价格 id（用于苹果支付）
	 * @return [type] [description]
	 */
	public static function getIosProductPriceId(){
		$ret=  [
					['price'=>1], 
					['price'=>3], 
					['price'=>6],
					['price'=>8],
					['price'=>18],
					['price'=>28],
					['price'=>40],
					['price'=>60],
					['price'=>98],
					['price'=>108],
					['price'=>198],
					['price'=>298],
					['price'=>388],
					['price'=>488],
					['price'=>588],
					['price'=>998],
					['price'=>1298],
					['price'=>1598],
					['price'=>1998],
					['price'=>2298],
					['price'=>3998],
					['price'=>4998],
					['price'=>5898],
					['price'=>6498],
			    ];
		foreach ($ret as $key => $value) {
			//添加productid
			$ret[$key]['productid']="myb_in_app_purchase_".$value['price']."_paycorrect";
		}
		return $ret;
	}

	/**
	 * 通过价格获取ios苹果支付产品id
	 */
	public static function getIosProductidByPrice($price){
		$all=self::getIosProductPriceId();
		foreach ($all as $key => $value) {
			if($price==$value['price']){
				return $value['productid'];
			}
		}
		return null;
	}
}
