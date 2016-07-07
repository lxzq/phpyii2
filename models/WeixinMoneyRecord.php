<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "weixin_money_record".
 *
 * @property integer $id
 * @property integer $user_id
 * @property double $money
 * @property string $add_time
 * @property integer $status
 * @property string $order_no
 * @property integer $child_id
 * @property integer $type
 * @property double $should_money
 * @property string $notes
 */
class WeixinMoneyRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weixin_money_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'child_id', 'type'], 'integer'],
            [['money', 'should_money'], 'number'],
            [['add_time'], 'safe'],
            [['order_no', 'notes'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'money' => '实际收费金额',
            'add_time' => 'Add Time',
            'status' => '0 失败 1 成功',
            'order_no' => 'Order No',
            'child_id' => 'Child ID',
            'type' => '1 充值 2 优惠',
            'should_money' => '应收金额',
            'notes' => 'Notes',
        ];
    }
}
