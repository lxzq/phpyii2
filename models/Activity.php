<?php
namespace app\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class Activity extends Model
{
    public $activity_id;
    public $activity_name;
    public $activity_centent;
    public $intro;
    public $activity_image;
    public $activity_createtime;
    public $activity_starttime;
    public $activity_endtime;
    public $activity_theme;
    public $activity_number;
    public $activity_address;
    public $activity_tel;
    public $activity_host;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_name', 'activity_image', 'intro'], 'required'],
            [['activity_centent'], 'string'],
            [['activity_createtime', 'activity_starttime', 'activity_endtime'], 'safe'],
            [['activity_name'], 'string', 'max' => 100],
            [['activity_image'], 'string', 'max' => 500],
            [['intro'], 'string', 'max' => 500]
        ];
    }

    public function submitData(){
        return  $this->validate();
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
}
