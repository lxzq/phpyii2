<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "other_money_record".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $pay_name
 * @property string $add_date
 * @property integer $pay_type
 * @property double $pay_money
 * @property integer $check_status
 * @property integer $yii_user_id
 * @property string $receipt_id
 * @property string $notes
 */
class OtherMoneyRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'other_money_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id'], 'required'],
            [['shop_id', 'pay_type', 'check_status', 'yii_user_id'], 'integer'],
            [['add_date'], 'safe'],
            [['pay_money'], 'number'],
            [['pay_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'pay_name' => '收入名称',
            'add_date' => '收入日期',
            'pay_type' => '支付类型 1现金 2 银联 3 信用卡 4微信支付 5 支付宝',
            'pay_money' => '实际收入金额',
            'check_status' => '审核状态 0待审核 1店长审核完成 ',
            'yii_user_id' => '下单操作用户ID',
            'receipt_id'=>'收据编号',
            'notes'=>'收款事由'
        ];
    }

    public function getShop(){
        return $this->hasOne(ShopInfo::className(),['id'=>'shop_id']);
    }

    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'yii_user_id']);
    }
}
