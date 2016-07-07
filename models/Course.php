<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:27
 */

namespace app\models;


use yii\base\Model;

class Course extends Model
{
    public $id;
    public $org_id;
    public $name;
    public $notes;
    public $logo;
    public $describe;
    public $course_type;
    public $class_time;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['org_id'], 'required'],
            [['org_id'], 'integer'],
            [['name'], 'string'],
            [['logo'], 'string', 'max' => 150],
            [['describe'], 'string', 'max' => 100],
            [['class_time'], 'string', 'max' => 50]
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