<?php
namespace common\components;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * 
 * @author Administrator
 *
 */
class BaseController extends Controller
{
    public $data = array();

    /**
     * 输出信息
     *
     * @param array $ret
     * @param string $type 输出格式  html json xml
     * @param array $xmlconf XML配置
     */
    public function outputMessage($ret, $type = 'JSON', $xmlconf = array('cdata' => array(), 'item' => 'item','root' => 'root'))
    {
        switch (strtoupper($type)) {
            case 'JSON':
                header('Content-type:text/html;charset=utf-8');
                $output = Json::encode($ret);
                break;

            case 'XML':
                header('Content-type:application/xml;charset=utf-8');
                $output = $this->outputXML($ret, $xmlconf);
                break;

            default:
            case 'HTML':
                header('Content-type:text/html;charset=utf-8');
                $output = var_export($ret, true);
                break;
        }
        Yii::$app->end($output);
    }

    /**
     * 把数组信息转化成xml格式
     *
     * @param array $data
     * @param array $config = array('cdata' => array(), 'item' => 'row', 'level' => 1)
     * @return string
     */
    protected function outputXML ($data, $config = array('cdata' => array(), 'item' => 'row', 'root'=>'root','level' => 1))
    {
        isset($config['cdata']) || $config['cdata'] = array();
        isset($config['item']) || $config['item'] = 'row';
        isset($config['level']) || $config['level'] = 1;
        isset($config['root']) || $config['root'] = 'root';

        $s = $config['level'] == 1 ? "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<" .$config['root'].">\n" : '';
        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        $space = str_repeat("\t", $config['level']);
        foreach ($data as $k => $v) {
            is_numeric($k) && $k = $config['item'];
            if (is_array($v) || is_object($v)) {
                $s .= $space . "<" . $k . ">\n" . self::outputXML($v, array('cdata' => $config['cdata'], 'item' => $config['item'], 'level' => $config['level'] + 1)) . $space . "</" . $k . ">\n";
            } else {
                $isCData = ! empty($v) && in_array($k, $config['cdata']);
                $s .= $space . "<" . $k . ">" . ($isCData ? '<![CDATA[' : '') . htmlspecialchars_decode($v, ENT_NOQUOTES) . ($isCData ? ']]>' : '') . "</" . $k . ">\n";
            }
        }
        return $config['level'] == 1 ? $s . "</" .$config['root'].">" : $s;
    }

    /**
     * api接口返回数据
     * die 替换 Yii::$app->end($ret) 使得header生效
     * @param unknown $intStatus
     * @param unknown $arrData
     */
    public function renderJson($intStatus, $arrData = array()) {
    	$request = Yii::$app->request;
    	
    	header("Content-Type:application/json;charset=utf-8");
    	$result['errno'] = $intStatus;
    	if(!empty($arrData)) {
    		$result['data'] = $arrData;
    	}
    	//判断是否jsonp调用
    	$callback = $request->get('callback');
    	if(!$callback){
    		$callback = $request->post('callback');
    	}    	
    	if (empty($callback)) {
    		$ret = json_encode($result);
    		die($ret);
    	} else {
    		//jsonp调用
    		$ret = '/**/'. $callback . ' && ' .
    				$callback . '(' .
    				json_encode($result) . ');';
    		die($ret);
    	}
    }
    
    /**
     * Error
     *
     * @param string $type
     * @return string
     */
    public function error($type)
    {
        switch ($type) {
            case 404:
                echo "404";
                break;

            default:
                # code...
                break;
        }
    }
}