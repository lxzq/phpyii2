<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/26
 * Time: 15:01
 */

namespace wxmanage\controllers;



use Faker\Provider\Uuid;
use wxbackend\models\CameraInfo;
use OSS\OssClient;
use Yii;
use yii\base\Exception;
use yii\data\Pagination;
use yii\web\Controller;
use wxbackend\models\VideoForm;
use yii\web\UploadedFile;


class VideoController extends  Controller
{


    public function actionVideo()
    {
        $params = $_REQUEST;
        $model = new VideoForm();
        if(!empty($params["id"])){
            $camra=CameraInfo::findOne($params["id"]);
            $model->videoName = $camra["name"];
            $model->videoEdu =  $camra["project_id"];
            $model->notes = $camra["notes"];
            $model->videoCode = $camra["code"];
            $model->videoUrl =  $camra["uri"];
            $model->videoImage = $camra["image"];
            $model->id = $camra["id"];
            $model ->type = $camra["type"];
        }
        if ($model->load(Yii::$app->request->post()) && $model->submitData()) {
            return $this->goBack();
        } else {
            return $this->render('video',[
                'model' => $model,
            ]);
        }
    }

    public function actionSave(){
        $params=$_REQUEST["VideoForm"];
        if(!empty($_REQUEST["id"])){
            $cameraInfo=CameraInfo::findOne($_REQUEST["id"]);
        }else{
            $cameraInfo = new CameraInfo();
            $max = CameraInfo::maxSort() + 1;
            $cameraInfo->sort_num = $max;
        }
        $cameraInfo->project_id = $params["videoEdu"];
        $cameraInfo->name  =  $params["videoName"];
        $cameraInfo->code = $params["videoCode"];
        $cameraInfo->uri =  $params["videoUrl"];
        $cameraInfo->notes = $params["notes"];
        $cameraInfo->type = $params["type"];
        $cameraInfo->image = $_REQUEST["videoImage"];
        $cameraInfo->save();
       return $this->redirect("/video/list");
    }

    public function actionSort(){
        $id = $_REQUEST["id"];
        $sort = $_REQUEST["sort"];
        if(!empty($id)){
            $cameraInfo=CameraInfo::findOne($_REQUEST["id"]);
        }
        $cameraInfo->sort_num = $sort;
        try{
            $cameraInfo->save();
        }catch (Exception $x){
        }
        return $this->redirect("/video/list");
    }


    public function actionDel(){
        $id = $_REQUEST["id"];
        if(!empty($id)){
         CameraInfo::findOne($id)->delete();
        }
        return $this->redirect("/video/list");
    }

    public function actionList(){
        $list = CameraInfo::find()->orderBy("sort_num desc ");
        $pages = new Pagination(['totalCount' => $list->count(),'pageSize' => '5']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
       return $this->render('list',[
            'list' => $data,
            'pages' => $pages
        ]);
      }

    public function actionUpload(){
        $accessKeyId = "ukZxh4uCD6VWvwd2";
        $accessKeySecret = "H6NsydEk5SJUXb3pWYyj48etiWUgeq";
        $endpoint = "oss-cn-hangzhou.aliyuncs.com";
        $bucket = "jiajiabang";
        $file = UploadedFile::getInstanceByName("upfile");
        $uuid = Uuid::uuid();
        $temp = dirname(dirname(__FILE__))."\web\upload"."/". $uuid .'.'. $file->extension ;
        $object = "images/weixin/".$uuid.".jpg";
        $file->saveAs($temp);
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $ossClient->uploadFile($bucket,$object,$temp);
            unlink($temp);
         } catch (OssException $e) {
            print $e->getMessage();
       }print'http://image.happycity777.com/'.$object;
    }

}