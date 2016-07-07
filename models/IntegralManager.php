<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "integral_manager".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $op_val
 */
class IntegralManager extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'integral_manager';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'op_val'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '积分代码',
            'name' => '积分名称',
            'op_val' => '积分运算值',
        ];
    }


}
