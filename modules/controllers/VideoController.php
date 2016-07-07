<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/26
 * Time: 15:01
 */

namespace app\modules\controllers;


use app\models\ShopInfo;
use Faker\Provider\Uuid;
use app\models\CameraInfo;
use OSS\OssClient;
use Yii;
use yii\base\Exception;
use yii\data\Pagination;
use yii\web\Controller;
use app\models\VideoForm;
use yii\web\UploadedFile;
use yii\filters\AccessControl;


class VideoController extends Controller
{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout = 'layout';

    /**
     * accesscontrol
     */

    /**
     * @用户授权规则
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['list', 'video', 'save', 'sort', 'del', 'upload', 'dir', 'cutpic', 'uploadoss', 'lb'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionVideo()
    {
        $params = $_REQUEST;
        $model = new VideoForm();
        if (!empty($params["id"])) {
            $camra = CameraInfo::findOne($params["id"]);
            $model->videoName = $camra["name"];
            $model->videoEdu = $camra["project_id"];
            $model->notes = $camra["notes"];
            $model->videoCode = $camra["code"];
            $model->videoUrl = $camra["uri"];
            $model->videoImage = $camra["image"];
            $model->id = $camra["id"];
            $model->type = $camra["type"];
        }

        $shops = ShopInfo::find()->all();
        $shoplist = [];
        foreach ($shops as $shop) {
            $shoplist[$shop["id"]] = $shop["name"];
        }
        if ($model->load(Yii::$app->request->post()) && $model->submitData()) {
            return $this->goBack();
        } else {
            return $this->render('video', [
                'model' => $model,
                'shops' => $shoplist
            ]);
        }
    }

    public function actionSave()
    {
        $params = $_REQUEST["VideoForm"];
        if (!empty($_REQUEST["id"])) {
            $cameraInfo = CameraInfo::findOne($_REQUEST["id"]);
        } else {
            $cameraInfo = new CameraInfo();
            $max = CameraInfo::maxSort() + 1;
            $cameraInfo->sort_num = $max;
            $cameraInfo->lb = 0;
        }
        $cameraInfo->project_id = $_REQUEST["videoEdu"];
        $cameraInfo->name = $params["videoName"];
        $cameraInfo->code = $_REQUEST["videoCode"];
        $cameraInfo->uri = $_REQUEST["videoUrl"];
        $cameraInfo->notes = $params["notes"];
        $cameraInfo->type = $_REQUEST["type"];
        $cameraInfo->image = $_REQUEST["videoImage"];
        $cameraInfo->save();
        return $this->redirect("/admin/video/list");
    }

    public function actionSort()
    {
        $id = $_REQUEST["id"];
        $sort = $_REQUEST["sort"];
        if (!empty($id)) {
            $cameraInfo = CameraInfo::findOne($_REQUEST["id"]);
        }
        $cameraInfo->sort_num = $sort;
        try {
            $cameraInfo->save();
        } catch (Exception $x) {
        }
        return $this->redirect("/admin/video/list");
    }


    public function actionDel()
    {
        $id = $_REQUEST["id"];
        if (!empty($id)) {
            $camera = CameraInfo::findOne($id);
            if ($_REQUEST["status"] == 0) {
                $camera->status = 1;
            }else{
                $camera->status = 0;
            }
            $camera->save();
        }
        return $this->redirect("/admin/video/list");
    }

    public function actionList()
    {
        $sql = [];
        $shopId = "";
        if (!empty($_REQUEST["name"])) {
            $sql = ['like', 'name', $_REQUEST["name"]];
        }
        if (!empty($_REQUEST["shopId"])) {
            $sql = ['=', 'project_id', $_REQUEST["shopId"]];
            $shopId = $_REQUEST["shopId"];
        }
        $shops = ShopInfo::find()->all();
        $list = CameraInfo::find()->where($sql)->orderBy("sort_num desc ");
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        if (!empty($_REQUEST["shopId"])) {
            $pages->params = ['shopId' => $shopId];
        } else {
            $pages->params = [];
        }
        return $this->render('list', [
            'list' => $data,
            'pages' => $pages,
            'shops' => $shops,
            'shopId' => $shopId
        ]);
    }

    /**
     * 上传图片到服务器
     */
    public function actionUpload()
    {
        $file = UploadedFile::getInstanceByName("upfile");
        $ext = time() . '.' . $file->extension;;//生成的引用路径
        $temp = Yii::$app->basePath . "/web/avatar/" . $ext;
        $file->saveAs($temp);
        print '/avatar/' . $ext;
    }

    /**
     * @裁剪图片后上传到阿里云
     */
    public function actionCutpic()
    {
        $path = "/avatar/";
        $targ_w = 1080;
        $targ_h = 570;
        $jpeg_quality = 100;
        $src = $_REQUEST["f"];
        $x = $_REQUEST["x"];
        $y = $_REQUEST["y"];
        $w = $_REQUEST["w"];
        $h = $_REQUEST["h"];
        $t = $_REQUEST["t"];
        $srcimage = Yii::$app->basePath . '/web' . $src;//真实的图片路径
        if ('jpg' == $t || 'JPG' == $t) {
            $img_r = imagecreatefromjpeg($srcimage);
        } else if ('png' == $t || 'PNG' == $t) {
            $img_r = imagecreatefrompng($srcimage);
        }
        $ext = $path . time() . ".jpg";//生成的引用路径
        $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $targ_w, $targ_h, $w, $h);
        $img = Yii::$app->basePath . '/web/' . $ext;//真实的图片路径
        if (imagejpeg($dst_r, $img, $jpeg_quality)) {
            $uuid = Uuid::uuid();
            $object = "images/weixin/" . $uuid . ".jpg";
            $accessKeyId = "ukZxh4uCD6VWvwd2";
            $accessKeySecret = "H6NsydEk5SJUXb3pWYyj48etiWUgeq";
            $endpoint = "oss-cn-hangzhou.aliyuncs.com";
            $bucket = "jiajiabang";
            try {
                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
                $ossClient->uploadFile($bucket, $object, $img);
                unlink($img);
                unlink($srcimage);
            } catch (OssException $e) {
                print $e->getMessage();
            }
            echo 'http://image.happycity777.com/' . $object;
        } else {
            echo '0';
        }
        exit;
    }

    /**
     * 上传图片到阿里云
     */
    public function actionUploadoss()
    {
        $file = UploadedFile::getInstanceByName("upfile");
        $ext = time() . '.' . $file->extension;;//生成的引用路径
        $temp = Yii::$app->basePath . "/web/avatar/" . $ext;
        if ($file->saveAs($temp)) {
            $uuid = Uuid::uuid();
            $object = "images/weixin/" . $uuid . ".jpg";
            $accessKeyId = "ukZxh4uCD6VWvwd2";
            $accessKeySecret = "H6NsydEk5SJUXb3pWYyj48etiWUgeq";
            $endpoint = "oss-cn-hangzhou.aliyuncs.com";
            $bucket = "jiajiabang";
            try {
                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
                $ossClient->uploadFile($bucket, $object, $temp);
                unlink($temp);
            } catch (OssException $e) {
                print $e->getMessage();
            }
            echo 'http://image.happycity777.com/' . $object;
        } else
            echo 'up load error';
    }

    public function actionDir()
    {
        print  dirname(dirname(dirname(__FILE__)));
    }

    public function actionLb()
    {
        $id = $_REQUEST["id"];
        $lb = $_REQUEST["lb"];
        if ($lb == 0) $lb = 1;
        else $lb = 0;
        $cam = CameraInfo::findOne($id);
        $cam->lb = $lb;
        $cam->save();
        return $this->redirect("/admin/video/list");
    }

}