<?php



namespace common\tool;
use Faker\Provider\Uuid;
use OSS\OssClient;
use common\models\CourseOrder;
use Yii;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/11
 * Time: 16:03
 */
class ApiConstant
{

   public static $perpage=10;
   //周围范围（米）
   public static $range=100;
   //统计时间
   public static $countTime=1;


   /**
    * 根据订单号生成二维码
    */
   public  function getImgUrl($classid,$orderno){
      /*        $options = array(
                  'http' => array(
                      'method' => 'GET',//or GET
                      'header' => 'Content-type:application/x-www-form-urlencoded',
                      'timeout' => 15 * 60 // 超时时间（单位:s）
                  )
              );
              $context = stream_context_create($options);
              $result = file_get_contents("http://weixin.happycity777.com/discuz/plugin.php?id=tom_pintuan:token", false, $context);
              $result=$data=json_decode($result, true);*/
      $errorCorrectionLevel = 'L';//容错级别
      $matrixPointSize = 6;//生成图片大小
      //生成二维码图片
      \QRcode::png("http://weixin.happycity777.com/growth/check?classid=".$classid.'&&no='.$orderno, time().'.png', $errorCorrectionLevel, $matrixPointSize, 2);
      $imgurl= Yii::$app->basePath.'/web/'.time().'.png';
      $uuid = Uuid::uuid();
      $object = "images/weixin/".$uuid.".jpg";
      $accessKeyId = "ukZxh4uCD6VWvwd2";
      $accessKeySecret = "H6NsydEk5SJUXb3pWYyj48etiWUgeq";
      $endpoint = "oss-cn-hangzhou.aliyuncs.com";
      $bucket = "jiajiabang";
      try {
         $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
         $ossClient->uploadFile($bucket,$object,$imgurl);
         unlink($imgurl);
      } catch (OssException $e) {
         print $e->getMessage();
      }
      return 'http://image.happycity777.com/' .$object;
   }

}
