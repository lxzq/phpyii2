<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/1
 * Time: 11:59
 */

namespace app\modules\controllers;


use app\models\ShopRegistration;
use OSS\OssClient;

use app\models\UserYuyue;
use yii\web\Controller;
use yii\data\Pagination;
use yii\filters\AccessControl;
use Yii;

class YuyueController  extends  Controller
{

    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';

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
                        'actions' => ['login','captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['list','del','test','shopreg'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionList(){

        $sql = [];
        if(!empty($_REQUEST["childName"])){
            $sql = ['like','child_name',$_REQUEST["childName"]];
        }
        if(!empty($_REQUEST["userName"])){
            $sql = ['like','user_name',$_REQUEST["userName"]];
        }
        if(!empty($_REQUEST["phone"])){
            $sql = ['like','phone',$_REQUEST["phone"]];
        }
        $list = UserYuyue::find()->where($sql)->orderBy("reg_date desc ");
        $pages = new Pagination(['totalCount' => $list->count("id"),'pageSize' => '5']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('list',[
            'list' => $data,
            'pages' => $pages
        ]);
    }

    public function actionDel(){
        $id = $_REQUEST["id"];
        if(!empty($id)){
            UserYuyue::findOne($id)->delete();
        }
        return $this->redirect("/admin/yuyue/list");
    }


    public function actionShopreg(){
        $shopId = Yii::$app->user->identity->shopId;
        $where = [];
        if(!empty($_REQUEST["phone"])){
            $where["shop_registration.phone"] = $_REQUEST["phone"];
        }
        if(!empty($_REQUEST["username"])){
            $where["shop_registration.username"] = $_REQUEST["username"];
        }
        if(!empty($shopId))
        $where["shop_registration.shopId"] = $shopId;
        $list = ShopRegistration::find()->where($where)->joinWith('shop')->orderBy("shop_registration.id desc");
        $pages = new Pagination(['totalCount' => $list->count("shop_registration.id"),'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('shopreg',[
            'list' => $data,
            'pages' => $pages
        ]);
    }

    public function actionTest(){
       /* $accessKeyId = "ukZxh4uCD6VWvwd2";
        $accessKeySecret = "H6NsydEk5SJUXb3pWYyj48etiWUgeq";
        $endpoint = "oss-cn-hangzhou.aliyuncs.com"; // 具体参照[这里]({{doc/[8]用户手册/访问域名和数据中心.md}})
        $bucket = "jiajiabang";
        $object = "images/jjb.jpg";
        $filePath = "d:/ic_vip.png";
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $ossClient->uploadFile($bucket, $object, $filePath);
        } catch (OssException $e) {
            print $e->getMessage();
        }
        print(__FUNCTION__ . ": OK" . "\n");*/

       echo 'sss';
    }

}