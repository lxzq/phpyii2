<?php

namespace app\modules\controllers;

use Yii;
use yii\web\Controller;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\Picture;
use app\modules\weixin\BaseController;
/**
 * MaterialNewsController implements the CRUD actions for MaterialNews model.
 */
class UploadController extends BaseController
{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
    /**
     * 上传图片
     */
    public function actionUpload()
    {
        header('Access-Control-Allow-Origin: http://localhost:8888'); //设置http://www.baidu.com允许跨域访问
        header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            file_put_contents('./cache/files',json_encode($model->file));
            $time=date("Ymd");
            $path="uploads/$time/";
            if(!file_exists($path)){
                mkdir($path,0777,true);
            }
            $name=call_user_func_array('uniqid',array());
            $file['path']=$path.$name.".".$model->file->extension;
            $file['md5']=md5_file($model->file->tempName);
            $file['sha1'] = sha1_file($model->file->tempName);
            $picture=new Picture();
            $info=$picture->isFile($file);
            if(empty($info))
            {
                if ($model->upload($file['path'])) {
                    // 文件上传成功
                    $picture->md5=$file['md5'];
                    $picture->path="/".$file['path'];
                    $picture->sha1=$file['sha1'];
                    $picture->status='1';
                    $picture->add_time=time();
                    if($picture->save())
                    {
                        $return=$file;
                        $return['status'] = $picture->status;
                        $return['id']=$picture->id;
                        $return['path']=$picture->path;
                    }

                }else{
                    $return['status'] = 0;
                    $error   = $model->getErrors();
                    $return['info']=$error['file'][0];
                }
            }else{
                $return['id']=$info['id'];
                $return['path']=$info['path'];
                $return['status']='1';

            }
            echo json_encode($return);
        }else{
            return $this->render("add",['model'=>$model]);
        }
    }
    public function actionUploadWeixin()
    {
        header('Access-Control-Allow-Origin: *'); //设置http://www.baidu.com允许跨域访问
        header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
       // Header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if(substr($model->file->type,0,5)=="video"){
                $time = date("Ymd");
                $path = "uploads/ueditor/video/$time/";
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $name = $model->file->name;
                $file['path'] = $path . $name;
                if ($model->upload($file['path'])) {
                    $return['state'] = "SUCCESS";
                    $return['url'] = $this->upload_video_to_wechat("/" . $file['path']);
                    $size = $model->file->size;
                    $return['size'] = "$size";
                    $return['name'] = $name;
                    $return['w']="null";
                    $return['h']="null";
                } else {
                    $return['state'] = "ERROR";
                    $return['path'] = $file['path'];
                }
                $result = json_encode($return);
                if (isset($_GET["callback"])) {
                    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
                    } else {
                        echo json_encode(array(
                            'state' => 'callback参数不合法'
                        ));
                    }
                } else {
                    echo $result;
                }
            }else if(substr($model->file->type,0,5)=="image"){
                $time=date("Ymd");
                $path="uploads/ueditor/image/$time/";
                if(!file_exists($path)){
                    mkdir($path,0777,true);
                }
                $name=$model->file->name;
                $file['path']=$path.$name;
                if ($model->upload($file['path'])) {
                    $return['state'] = "SUCCESS";
                    $return['url']=$this->upload_image_to_wechat( "/".$file['path']);
                    //$return['url']='http://mmbiz.qpic.cn/mmb...hicpQNMdNuB5wjJrPImyg/0';
                    $image_info=getimagesize($file['path']);
                    $size=$model->file->size;
                    $return['size']="$size";
                    $return['name']=$name;
                    $return['w']=$image_info[1];
                    $return['h']=$image_info[0];
                }else{
                    $return['state']="ERROR";
                    $return['path']=$file['path'];
                }
                $result= json_encode($return);
                if (isset($_GET["callback"])) {
                    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
                    } else {
                        echo json_encode(array(
                            'state'=> 'callback参数不合法'
                        ));
                    }
                } else {
                    echo $result;
                }
                //echo '{"url":"http://7teaoa.com1.z0.glb.clouddn.com/pic1.jpg", "state": "SUCCESS", "name": "pic1.jpg","size": "228671","w": "509","h": "354"}';
            }


        }else{
            return $this->render("add",['model'=>$model]);
        }
    }
    public function actionUploadVideo()
    {
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            $time = date("Ymd");
            $path = "uploads/ueditor/video/$time/";
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            var_dump(substr($model->file->type,0,5)=="image");die;
            $name = $model->file->name;
            $file['path'] = $path . $name;
            if ($model->upload($file['path'])) {
                $return['state'] = "SUCCESS";
                $return['url'] = $this->upload_video_to_wechat("/" . $file['path']);
                //$return['url']='http://mmbiz.qpic.cn/mmb...hicpQNMdNuB5wjJrPImyg/0';
                $image_info = getimagesize($file['path']);
                $size = $model->file->size;
                $return['size'] = "$size";
                $return['name'] = $name;
            } else {
                $return['state'] = "ERROR";
                $return['path'] = $file['path'];
            }
            $result = json_encode($return);
            if (isset($_GET["callback"])) {
                if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                    echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
                } else {
                    echo json_encode(array(
                        'state' => 'callback参数不合法'
                    ));
                }
            } else {
                echo $result;
            }
        }
    }
}
