<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/12
 * Time: 9:52
 */

namespace app\models;


use yii\base\Model;

class ProjectForm extends Model
{

    public $id;
    public $shopId;
    public $name;
    public $image;
    public $notes;
    public $describe;

    public function rules()
    {
        return [
            [[ 'name','shopId'], 'required','message'=>'{attribute}不能为空！']
        ];
    }
}