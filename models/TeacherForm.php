<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/9
 * Time: 13:17
 */

namespace app\models;

use yii\base\Model;
class TeacherForm extends Model
{
    public $id;
    public $orgId;
    public $name;
    public $sex;
    public $workYears;
    public $address;
    public $notes;
    public $phone;
    public $weixinUserId;

    public function rules()
    {
        return [
             [['orgId', 'name',], 'required','message'=>'{attribute}不能为空！']
        ];
    }

}