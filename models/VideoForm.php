<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/26
 * Time: 15:08
 */

namespace app\models;

use Yii;
use yii\base\Model;
class VideoForm extends Model
{

    public $videoEdu;
    public $videoName;
    public $notes;
    public $videoCode;
    public $videoUrl;
    public $videoImage;
    public $id;
    public $type;
    public $lbImage;
   /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['videoEdu', 'videoName',' videoCode','videoUrl','type'], 'required','message'=>'{attribute}不能为空！']
        ];
    }

    public function submitData(){
        return $this->validate();
    }
}