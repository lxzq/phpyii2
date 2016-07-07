<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:27
 */

namespace app\models;


use yii\base\Model;

class CustomerVisitForm extends Model
{
    public $id;
    public $cusromer_record_id;
    public $content;
    public $add_time;
    public $user;
    public $notes;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }
}