<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "picture".
 *
 * @property string $id
 * @property string $path
 * @property string $url
 * @property string $md5
 * @property string $sha1
 * @property integer $status
 * @property string $add_time
 */
class Picture extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'picture';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'add_time'], 'integer'],
            [['path', 'url'], 'string', 'max' => 255],
            [['md5'], 'string', 'max' => 32],
            [['sha1'], 'string', 'max' => 40]
        ];
    }

    /**
     * 检测当前上传的文件是否已经存在
     * @param  array   $file 文件上传数组
     * @return boolean       文件信息， false - 不存在该文件
     */
    public function isFile($file){
        $info=Picture::find()->where(['md5'=>$file['md5'],'sha1'=>$file['sha1']])->one();
        return $info;
    }
}
