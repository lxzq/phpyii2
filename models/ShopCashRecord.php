<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shop_cash_record".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property integer $type
 * @property string $add_time
 * @property double $total_money
 * @property integer $user_id
 * @property integer $is_finance
 */
class ShopCashRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_cash_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'type', 'add_time', 'total_money', 'user_id'], 'required'],
            [['shop_id', 'type', 'user_id'], 'integer'],
            [['add_time'], 'safe'],
            [['total_money'], 'number']
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
            'type' => '类型：1课程收入 2 其他收入 ',
            'add_time' => 'Add Time',
            'total_money' => 'Total Money',
            'user_id' => '操作人',
            'is_finance'=>'1 门店录入 2财务录入'
        ];
    }
}
