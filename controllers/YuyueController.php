<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/1
 * Time: 11:59
 */

namespace wxmanage\controllers;


use OSS\OssClient;

use wxbackend\models\UserYuyue;
use yii\web\Controller;
use yii\data\Pagination;
class YuyueController  extends  Controller
{

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
        return $this->redirect("/yuyue/list");
    }

    public function actionTest(){
        $accessKeyId = "ukZxh4uCD6VWvwd2";
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
        print(__FUNCTION__ . ": OK" . "\n");
    }

}