<?php
namespace app\models;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\Picture;
class UploadForm extends Model{

    public $file;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false],
        ];
    }

    /**
     * @return bool
     */
    public function upload($save_name)
    {
        if ($this->validate()) {

            $this->file->saveAs($save_name);

            return true;
        } else {
            return false;
        }
    }
    public function uploadPicture($files)
    {
            $this->file = UploadedFile::getInstance($this, 'file');
            $time=date("Ymd");
            $path="uploads/$time/";
            if(!file_exists($path)){
                mkdir($path,0777,true);
            }
            //$name=call_user_func_array('uniqid',array());
            $file['path']=$path.$this->file->name;
            $file['md5']=md5_file($this->file->tempName);
            $file['sha1'] = sha1_file($this->file->tempName);
            $picture=new Picture();
            $info=$picture->isFile($file);
            if(empty($info))
            {
                if ($this->upload($file['path'])) {
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
            return $return;
    }



}
?>