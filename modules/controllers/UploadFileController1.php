<?php

namespace app\modules\controllers;

use Yii;
use yii\web\Controller;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\Picture;
use yii\filters\AccessControl;
/**
 * MaterialNewsController implements the CRUD actions for MaterialNews model.
 */
class UploadFileController extends Controller
{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['upload'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    /**
     * 上传图片
     */
    public function actionUpload()
    {
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
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
}
