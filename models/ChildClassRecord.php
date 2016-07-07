<?php

namespace app\models;


use Yii;

/**
 * This is the model class for table "child_class_record".
 *
 * @property integer $id
 * @property integer $yii_user_id
 * @property integer $user_id
 * @property string $add_time
 * @property string $pay_name
 * @property string $shop_id
 * @property string $pay_no
 * @property double $total_money
 * @property integer $pay_type
 * @property integer $check_status
 * @property integer $add_type
 * @property string $notes
 * @property integer $is_delete
 * @property string $receipt_id
 * @property integer $money_type
 * @property integer $is_quit
 * @property string $update_time
 */
class ChildClassRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'child_class_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['yii_user_id', 'add_time', 'total_money', 'is_delete'], 'required'],
            [['yii_user_id', 'pay_type', 'check_status', 'add_type', 'is_delete','shop_id'], 'integer'],
            [['add_time'], 'safe'],
            [['total_money'], 'number'],
            [['notes'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'yii_user_id' => '下单操作用户ID',
            'add_time' => '下单时间',
            'total_money' => '实际支付总金额',
            'pay_type' => '支付类型 1现金 2 银联 3 信用卡 4微信支付 5 支付宝',
            'check_status' => '审核状态 0待审核 1店长审核完成 ',
            'add_type' => '0平台申报  1微信申报',
            'notes' => '清单描述',
            'is_delete' => '0 删除  1正常',
            'pay_name' => '支付名称',
            'pay_no' =>'流水记录号',
            'user_id' => '订单人',
            'shop_id'=>'店铺ID',
            'receipt_id'=>'收据编号',
            'money_type'=>'1表示全款 2 表示定金 3 表示余款',
            'is_quit'=>'0不允许退课 1 允许退课 2 提交审核退课',
            'update_time'=>'更新分成时间'
        ];
    }

    public function getYiiuser(){
        return $this->hasOne(User::className(),['id'=>'yii_user_id']);
    }

    public function getShop(){
        return $this->hasOne(ShopInfo::className(),["id"=>"shop_id"]);
    }

    public function getCourse(){
        return  $this->hasMany(CourseInfo::className(),["id"=>"course_id"])->viaTable('child_class',["record_id"=>"id"]);
    }

    public function getOrg(){
        return $this->hasOne(OrgShopInfo::className(),["shop_id"=>"shop_id"]);
    }

    public function getRecord(){
        return $this->hasMany(self::className(),['pay_no'=>'pay_no']);
    }
}
