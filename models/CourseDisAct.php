<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_dis_act".
 *
 * @property integer $id
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property integer $shop_id
 * @property integer $user_id
 * @property integer $status
 */
class CourseDisAct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_dis_act';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_date', 'end_date', 'shop_id'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['shop_id','status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title'=>'优惠标题',
            'start_date' => '优惠开始时间',
            'end_date' => '优惠结束时间',
            'shop_id' => '所属店铺',
            'user_id'=>'创建人',
            'status'=>'审核状态 0 未审核 1 审核通过 2 审核否定 '

        ];
    }

    public function getShop(){
        return $this->hasOne(ShopInfo::className(),["id"=>"shop_id"]);
    }

    public function getCourse(){
        return $this->hasMany(CourseDisCr::className(),['dis_act_id'=>'id']);
    }

    public function getUser(){
        return $this->hasOne(UserInfo::className(),['id'=>'user_id']);
    }

}
