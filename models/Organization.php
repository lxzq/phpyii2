<?php
namespace app\models;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 10:34
 */

class Organization extends Model{
    public $id;
    public $name;
    public $logo;
    public $notes;
    public $describe;
    public $shopIds;

    public function rules(){
        return [
            [['name','shopIds'], 'required'],
            [['notes'], 'string'],
            [['logo'], 'string'],
            [['describe'], 'string']
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