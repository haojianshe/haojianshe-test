<?php

namespace mis\controllers\live;

use Yii;
use mis\components\MBaseAction;
use mis\service\VideoSubjectItemService;
use common\models\myb\ImgMain;
use common\models\myb\ImgSecond;

//use common\service\DictdataService;
/**
 * 直播列表
 */
class LessonAction extends MBaseAction {

    public function run() {

        self::aaa();

        exit;
        #   self::setTeacherPic();
        #self::getLessonIds();
        #exit;
        #self::getSmallImg();
        #获取专题数据
        #self::getSmallImg();
        #获取远程图片 二级分类
        ####select count(*)  from myb_img_detail where mainid=1976  and secondid in (1990);  未完成，等待中
        #   主分类 1976 二级分类 1990 2000到3000条 有错误
        #二级
        #  $second = '1581,1629,1663,1670,1678,1686,1687,1688,1695,1703,1708,1711,1724,1725,1729,1730,1733,1736,1737,1740,1744,1745,1746,1747,1748,1750,1751,1754,1756,1757';
        #  $second = '1758,1761,1764,1771,1772,1773,1774,1775,1777,1778,1780,1781,1785,1786,1787,1789,1790,1792,1793,1794,1795,1796';
        #
        #$second = '1798,1803,1807,1809,1810,1811,1812,1815,1816,1820,1822,1826,1830,1831,1833,1836,1837,1840,1843,1845,1846,1847,1849,1850,1851,1852,1884,1885,1886,1887,1894,1895';
        #$second = '1900,1904,1908,1909,1910,1911,1912,1914,1915,1916,1917,1918,1920,1921,1922,1923,1925,1926,1927,1928,1929,1932,1933,1936,1939,1942,1945,1947,1948,1949,1950,1951,1952,1953,1954,1955,1957,1958,1959,1960,1961,1962,1963,1964,2027';
        //       $res = 'big';
        //        $url = '6e3a9429d19794345ee40ceefa7880a8.jpg';
//        $dir = 'main';
//        $dirNew = 'big';
//        self::copyFile($dir,$url,$dirNew);
//        $cid = 1905;
//        $cid = 1906;
//        $file = 'secai';
//         $cid = 1907;
//        $file = 'suxie';
//        
//        $second = 1729;
//        $file = 'secai';
//        $second = 1730;
//        $file = 'sumiao';
//        
        #$second = 1736;
        #$file = 'suxie';
//        $array = [
//            1744 => 'sumiao',
//            1745 => 'sucai',
//            1746 => 'suxie'
//        ];
//        $key = 1746;
//        foreach($array as $k=>$v){
//            if($key==$k){
//                $second = $k;
//                $file = $v;
//            }
//        }
//        #专题
//        $main = 701;
//        //储存目录
//        $param = 'xian2016gaofenjuan/' . $file;
//        //主类别
//        $where = " where mainid=$main and  secondid in ($second)"; # cid in ($cid) and
//        self::getFileImg($param . '/' . $res, $where, 'big');
        #$request = Yii::$app->request;
        # $secondid = trim($request->get("secondid")); #主分类

        self::getPic();
        #echo '休息';
        exit;
        $secondid = '1968,1969,1970,1971,1972,1973,1974,1980,1981,1982,1983,1984,2005,2006,2007,2008,2009,2010,2011,2012,2013,2032';

        $secondArr = explode(',', $secondid);

        set_time_limit(0);
        ini_set('memory_limit', '2024M');
        $connection = Yii::$app->db;
        $dir = 'dashizuopin';
        # $secondid = 1842;

        $str = '{"1842":"youjinjialinlaluo","1930":"molandi","1931":"saishang","1934":"andeluhuaisi","1935":"bishaluo","1937":"manai","1938":"touna","1940":"lieweitan","1941":"xiula","1943":"dejia","1944":"leinuoa","1965":"beiertemolisi","1966":"bonaer","1967":"angeer","1968":"dafenqi","1969":"fulansihaersi","1970":"fuluoyide","1971":"hansiheerbaiyin","1972":"laer","1973":"liebin","1974":"weimier","1980":"gaogeng","1981":"lubensi","1982":"delakeluowa","1983":"kuerbei","1984":"luotelieke","2005":"bugeluo","2006":"xiajiaer","2007":"dingtuolietuo","2008":"madisi","2009":"mengke","2010":"dali","2011":"mile","2012":"wotehaosi","2013":"yakeluyidawei","2032":"bijiasuo"}';
        $jsonArr = json_decode($str, 1);
        foreach ($jsonArr as $kk => $vv) {
            foreach ($secondArr as $kvk => $vvv) {
                if ($vvv == $kk) {
                    $file = $vv;
                    $sql = "select big from myb_img_detail where mainid=1956 and secondid=$vvv";
                    $command = $connection->createCommand($sql);
                    $data = $command->queryAll();
                    $filename = [];
                    foreach ($data as $k => $v) {
                        $filename[$k] = str_replace('/', '', strrchr($v['big'], '/'));
                    }
                    foreach ($filename as $key => $val) {
                        self::copyFile($dir, $val, $file);
                    }
                }
            }
        }


        #格勒兹
        # 1520,1521,1817,1818,1819,1821,1823,1824,1825,1827,1828,1829,1832,1834,1835,1841,1842,1930,1931,1934,1935,1937
        # ,1938,1940,1941,1943,1944,1965,1966,1967,1968,1969,1970,
        # 1971,1972,1973,1974,1980,1981,1982,1983,1984,2005,2006,2007,
        # 2008,2009,2010,2011,2012,2013,2032
        # 大师作品名称需要拼音生成库
        # 
        # 伦勃朗,莫奈,格勒兹,丢勒,梵高,惠斯勒,梅索尼埃,佐恩,柯罗,门采尔,米开朗基罗,尼古拉·费欣,萨金特,西斯莱,希施金,夏尔丹,尤金加林拉洛,莫兰迪,塞尚,
        # 安德鲁怀斯,毕沙罗,马奈,透纳,列维坦,修拉,德加,雷诺阿,贝尔特莫里斯,博纳尔,
        # 安格尔,达芬奇,弗兰斯哈尔斯,弗洛伊德,汉斯荷尔拜因,拉斐尔,列宾,维米尔,高更,鲁本斯,德拉克罗瓦,库尔贝,罗特列克,
        # 布格罗,夏加尔,丁托列托,马蒂斯,蒙克,达利,米勒,沃特豪斯,雅克路易大卫,毕加索
        #print_r($filename);
        #exit;
    }

    public static function aaa() {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $connection = Yii::$app->db;
        $sql = "select teacher_uid,rid from myb_correct_teacher_pic where f_catalog_id =0  limit 1000"; #  GROUP BY rid
        $command = $connection->createCommand($sql);
        $array = [];
        $data = $command->queryAll();
      # print_R($data);
      # exit;
        
        # 727729 
        foreach ($data as $key => $val) {
            $query = "select f_catalog_id,s_catalog_id from myb_correct where find_in_set({$val['rid']},example_pics) limit 1;";
            $comm = $connection->createCommand($query);
            $rest = $comm->queryOne();
            if($rest){
                #修改已经获取到的一级二级分类
               $sqls = 'update myb_correct_teacher_pic set f_catalog_id=' . $rest['f_catalog_id'] . ',s_catalog_id=' . $rest['s_catalog_id'] . ' where rid='. $val['rid'] . ' and teacher_uid='. $val['teacher_uid'];
               $comman = $connection->createCommand($sqls);
               $comman->execute();   
            }
            
        }
    }

//    public static function aaa() {
//        set_time_limit(0);
//        ini_set('memory_limit', '2024M');
//        $connection = Yii::$app->db;
//        $sql = "select correctid,example_pics,f_catalog_id,s_catalog_id from myb_correct WHERE  example_pics is not null and f_catalog_id is not null limit 100 ";
//        $command = $connection->createCommand($sql);
//        $array = [];
//        $data = $command->queryAll();
//        
//  
//        foreach($data as $key=>$val){
//            $arr[]=['example_ids'=>explode(',', $val['example_pics']),$val['correctid'],$val['f_catalog_id'],$val['s_catalog_id']
//            ];
//        }
//       foreach($arr as $k=>$v){
//           foreach($v as $kkk=>$vvv){
//               $array[] = $vvv;
//           }
//         }
//        $newArray = array_unique($array);
//        print_r($newArray);
//        exit;
//    }

    public static function setTeacherPic() {
        set_time_limit(0);
        ini_set('memory_limit', '2024M');
        $connection = Yii::$app->db;
        $sql = "SELECT GROUP_CONCAT(rid) as rids,rid,md5_string,count(*) as cou FROM ci_resource where md5_string is not null GROUP BY md5_string HAVING cou>1 order by cou desc";
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();
        foreach ($data as $k => $v) {
            $vstr = str_replace($v['rid'] . ',', '', $v['rids']);
            echo $sql = 'delete myb_correct_teacher_pic_copy from where rid in (' . $vstr . ');';
            exit;
            #$comman = $connection->createCommand($sql);
            #$comman->execute();
        }
    }

    # 

    /**
     * 获取跟着画图片
     */
    public static function getLessonIds() {
        set_time_limit(0);
        ini_set('memory_limit', '2024M');
        $connection = Yii::$app->db;
        $sql = 'select lessonid from myb_lesson where lessonid  not in (10018,
            10053,
            10074,
            10076,
            10078,
            10084,
            10091,
            10109,
            10110,
            10114,
            10122,
            10123,
            10134,
            10138,
            10169,
            10179,
            10180,
            10183,
            10218,
            10272,
            10302)';
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();
        $array = [];
        foreach ($data as $key => $val) {
            $array[$val['lessonid']] = self::getLessonPic($val['lessonid']);
        }
    }

    /**
     * 获取跟着画图片
     */
    public static function getLessonPic($val) {
        set_time_limit(0);
        ini_set('memory_limit', '2024M');
        $connection = Yii::$app->db;
        $sql = "SELECT a.picurl FROM myb_lesson_pic as a inner join myb_lesson_section as b on a.sectionid=b.sectionid where lessonid=$val order by b.listorder,a.listorder;";
        $command = $connection->createCommand($sql);
        $array = $command->queryAll();


        $save_path = $_SERVER ['DOCUMENT_ROOT'] . '/static/lessonimg/' . $val; //配置图片保存路径
        if (!is_dir($save_path)) {
            mkdir($save_path, 0777);
        }
        if (empty($array)) {
            return;
        }
        #图片数  = 总图片数减去一
        $count = count($array) - 1;
        if ($count > 4) {
            $ceil = floor($count / 4);
        }

        $newArray = [
            $array[0]['picurl'],
            $array[$ceil]['picurl'],
            $array[$ceil * 2]['picurl'],
            $array[$ceil * 3]['picurl'],
            $array[$ceil * 4]['picurl']
        ];

        if ($newArray) {
            foreach ($newArray as $kk => $vv) {
                if ($kk == 0) {
                    $filename = str_replace(strrchr($vv, '/'), 'cov.jpg', strrchr($vv, '/'));
                } else {
                    $filename = $kk . '.jpg';
                }
                ob_start(); //打开输出
                readfile($vv);
                $img = ob_get_contents(); //得到浏览器输出
                ob_end_clean(); //清除输出并关闭
                $fp2 = @fopen($save_path . '/' . $filename, "a");
                fwrite($fp2, $img); //向当前目录写入图片文件，并重新命名
                fclose($fp2);
            }
        }
    }

    public static function getPic() {
        set_time_limit(0);
        ini_set('memory_limit', '2024M');
        #$sql = 'select a.rid,a.img from ci_resource as a INNER JOIN myb_correct_teacher_pic as b on a.rid=b.rid limit 50000;';
        $sql = "select a.rid,a.img from ci_resource as a INNER JOIN myb_correct_teacher_pic as b on a.rid=b.rid where  md5_string=''";
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();

        $arr = [];
        foreach ($data as $key => $val) {
            $arr[$val['rid']] = json_decode($val['img'], 1)['n']['url'];
        }

//        $array = [];
//        foreach($arr as $k=>$v){
//            if(strpos($v,'.jpg')===false){
//                $array[$k] = $v;
//            }
//        }
//        
//        
//        foreach($array as $kh=>$vh){
//            if(strpos($vh,'.jpeg')===false){
//                $arrasy[$kh] = $vh;
//            }
//        }
//        
//       # echo json_encode($arrasy);
//        
//       print_r($arrasy);
//        exit;

        foreach ($arr as $key => $val) {
            $sql = 'update ci_resource set md5_string=' . md5_file($val) . ' where rid=' . $key;
            $comman = $connection->createCommand($sql);
            $comman->execute();
        }
    }

    //copy文件到指定文件夹
    public static function copyFile($dir, $url, $file) {
        $path = $_SERVER ['DOCUMENT_ROOT'] . '/static/img/' . $dir;
        $srcfile = $path . '/big/' . $url;
        $dstfile = $path . '/' . $file . '/' . $url;
        #mkdir(dirname($dstfile), 0777, true);
        if (!is_dir($path . '/' . $file)) {
            mkdir($path . '/' . $file, 0777);
        }
        copy($srcfile, $dstfile);
    }

    //获取远程图片
    public static function getFileImg($dri, $where = '', $name) {
        set_time_limit(0);
        ini_set('memory_limit', '2024M');
        $connection = Yii::$app->db;
        $sql = "select " . $name . " from myb_img_detail " . $where;
        $command = $connection->createCommand($sql);
        $dirName = '/static/pic/' . $dri;
        $data = $command->queryAll();
        foreach ($data as $k => $v) {
            self::GrabImage($v[$name], '', $dirName);
        }
    }

    public static function GrabImage($url, $filename = "", $dirName = '') {
        #if ($url == "") {
        #   return false;
        #  }
        #$url = 'http://img.meishubao.com/p/2015-11-18/smallfaad3dd3b436f97b09060177354d3b04.jpg';
        //如果$url地址为空，直接退出
        #if ($filename == "") {
        //如果没有指定新的文件名 
        # $ext = strrchr($url, ".");
        //得到$url的图片格式
//            if ($ext != ".gif" && $ext != ".jpg" && $ext != ".png") {
//                return false;
//            }
        //如果图片格式不为.gif或者.jpg，直接退出
        $filename = str_replace('/', '', strrchr($url, '/'));
        $save_path = $_SERVER ['DOCUMENT_ROOT'] . $dirName; //配置图片保存路径
        #$save_path = realpath ($save_path);//去除多余字符
        if (!is_dir($save_path)) {
            mkdir($save_path, 0777);
        }
        $filename = $save_path . '/' . $filename;
        //用天月面时分秒来命名新的文件名
        #}
        ob_start(); //打开输出
        readfile($url);
        $img = ob_get_contents(); //得到浏览器输出
        ob_end_clean(); //清除输出并关闭
        #$size = strlen($img); //得到图片大小
        $fp2 = @fopen($filename, "a");
        fwrite($fp2, $img); //向当前目录写入图片文件，并重新命名
        fclose($fp2);
        return $filename; //返回新的文件名
    }

    public static function getToken($url) {
        #echo $url;
        # exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //相当关键，这句话是让curl_exec($ch)返回的结果可以进行赋值给其他的变量进行，json的数据操作，如果没有这句话，则curl返回的数据不可以进行人为的去操作（如json_decode等格式操作）
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);      //单位 秒，也可以使用
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);     //注意，毫秒超时一定要设置这个
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 20000); //超时毫秒，cURL 7.16.2中被加入。从PHP 5.2.3起可使用
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        return curl_exec($ch);
        //$row=curl_getinfo($ch, CURLINFO_HTTP_CODE);
    }

    //写入主分类和二级分类数据
    public static function getMainData() {
        $row = self::getToken("https://m.meishubao.com/pictures/data2");
        $arr = json_decode($row, 1);
        $connection = Yii::$app->db;
        $str = " insert  into myb_img_main (mainid,title,icon,total,line,ctime) values ";
        $srcod = " insert  into myb_img_second (id,mainid,title,icon,total,datatype,ctime) values ";
        foreach ($arr['list'] as $key => $val) {
            $str .="('{$val['id']}','{$val['title']}','{$val['icon']}','{$val['total']}','{$val['line']}','" . time() . "'),";
            foreach ($val['child'] as $k => $v) {
                $srcod .="('{$v['id']}','{$val['id']}','{$v['title']}','{$v['icon']}','{$v['total']}','{$v['datatype']}','" . time() . "'),";
            }
        }
        $newstr = substr($str, 0, strlen($str) - 1) . ';';
        $newsrcodstr = substr($srcod, 0, strlen($srcod) - 1) . ';';
        $commandSql = $connection->createCommand($newstr);
        $comman = $connection->createCommand($newsrcodstr);
        $commandSql->execute();
        $comman->execute();
    }

    //获取所有图片数据

    public static function getSmallImg() {
        set_time_limit(0);
        $connection = Yii::$app->db;
        //大师作品 1956, 美术馆作品 1976, 中国名家作品 2015,速写 257
        //素描 255 , 色彩  256 , 设计 258 ,照片素材 443,留学作品库 1762
        $id = 701;
        $sql = "select subjectid from   myb_img_second_subject where mainid in ($id) and secondid  in (1576)";
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();
        $url = "https://m.meishubao.com/pictures/item2?count=100000&isbook=0&page=1&id=";
        foreach ($data as $key => $val) {
            $row = self::getToken($url . $val['subjectid']);
            $arr = json_decode($row, 1);
            if ($arr['pics']) {
                $str = " insert  into myb_img_detail (detailid,secondid,cid,mainid,small,big,hits,support,imgwidth,imgheight,tag,hadsupport,detail_desc,topicid,title) values ";
                foreach ($arr['pics'] as $k => $v) {
                    $tag = implode(',', $v['tag']);
                    $str .="('{$v['id']}','{$val['subjectid']}','{$v['cid']}','{$id}','{$v['small']}','{$v['big']}','{$v['viewcount']}','{$v['support']}','{$v['imgwidth']}','{$v['imgheight']}','{$tag}','{$v['hadsupport']}','{$v['desc']}','{$v['topicid']}','{$arr['title']}'),";
                }
                $newstr = substr($str, 0, strlen($str) - 1) . ';';
                $commandSql = $connection->createCommand($newstr);
                $commandSql->execute();
            }
        }
    }

    # 
    //取出专题二级封面图

    public static function getSubjectData() {
        set_time_limit(0);
        $connection = Yii::$app->db;
        $id = 701;
        $sql = "select id from   myb_img_second where mainid in ($id)";
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();

        $url = "https://m.meishubao.com/pictures/data2?count=1000&cid=";
        foreach ($data as $key => $val) {

            $row = self::getToken($url . $val['id']);
            $arr = json_decode($row, 1);

            if ($arr['list']) {
                $str = " insert  into myb_img_second_subject (subjectid,secondid,mainid,title,icon,total,datatype) values ";
                foreach ($arr['list'] as $k => $v) {
                    $str .="('{$v['id']}','{$val['id']}','{$id}','{$v['title']}','{$v['icon']}','{$v['total']}',2),";
                }
                $newstr = substr($str, 0, strlen($str) - 1) . ';';
                $commandSql = $connection->createCommand($newstr);
                $commandSql->execute();
            }
        }
    }

}
