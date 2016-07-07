<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/12
 * Time: 9:26
 */

namespace app\models;


use yii\base\Model;

class OrganizationAgreementForm extends Model
{
    public $userId;
    public $name;
    public $addTime;
    public $filePath;
    public $notes;
    public $orgId;
}