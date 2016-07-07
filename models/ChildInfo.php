<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "child_info".
 *
 * @property integer $id
 * @property string $nick_name
 * @property integer $sex
 * @property string $face
 * @property string $reg_date
 * @property string $birthday
 * @property integer $delete
 * @property integer $card_id
 * @property integer $card_code
 * @property string $phone
 * @property integer $shop_id
 * @property string $address
 * @property string $school
 * @property string $class_cc
 * @property string $second_name
 * @property string $second_phone
 * @property string $second_work
 */
class ChildInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'child_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sex', 'delete', 'card_id', 'card_code'], 'integer'],
            [['reg_date', 'birthday'], 'safe'],
            [['nick_name'], 'string', 'max' => 100],
            [['face'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nick_name' => 'Nick Name',
            'sex' => 'Sex',
            'face' => 'Face',
            'reg_date' => 'Reg Date',
            'birthday' => 'Birthday',
            'delete' => 'Delete',
            'card_id' => 'Card ID',
            'card_code' => 'Card Code',
            'phone'=>'phone',
            'shop_id'=>'shop_id',
            'address'=>'address',
            'school'=>'school',
            'class_cc'=>'class_cc',
            'second_name'=>'second_name',
            'second_phone'=>'second_phone',
            'second_work'=>'second_work'
        ];
    }

    public function getClass(){
        return $this->hasMany(CourseClass::className(),['id'=>'class_id'])->viaTable('child_class',['child_id'=>'id']);
    }

    public function getUser(){
        return $this->hasOne(UserInfo::className(),['id'=>'user_id'])->viaTable('user_child_info',['child_id'=>'id']);;
    }
}
