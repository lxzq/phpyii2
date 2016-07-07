<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teacher_info".
 *
 * @property integer $id
 * @property integer $org_id
 * @property string $name
 * @property integer $sex
 * @property integer $work_years
 * @property string $address
 * @property string $notes
 * @property string $phone
 * @property string $weixin_user_id
 */
class TeacherInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teacher_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['org_id', 'name'], 'required'],
            [['org_id', 'sex', 'work_years'], 'integer'],
            [['notes'], 'string'],
            [['name'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'org_id' => 'Org ID',
            'name' => 'Name',
            'sex' => '1 男  0 女',
            'work_years' => '工龄',
            'address' => 'Address',
            'notes' => 'Notes',
            'phone'=>'phone',
            'weixin_user_id'=>'微信用户ID'
        ];
    }

    public function getTeacher(){
        return $this->hasMany(CourseTeacher::className(),['teacher_id'=>'id']);
    }

    public function getWeixinUser(){
        return $this->hasOne(WeixinUser::className(),['id'=>'weixin_user_id']);
    }

}
