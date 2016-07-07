<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:27
 */

namespace app\models;


use yii\base\Model;

class CustomerRecordForm extends Model
{
    public $id;
    public $name;
    public $tel;
    public $content;
    public $add_date;
    public $shop_id;
    public $add_user;
    public $notes;
    public $sex;
    public $birthDate;
    public $childClass;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }
}