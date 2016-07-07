<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_yuyue".
 *
 * @property integer $id
 * @property string $child_name
 * @property string $birthday
 * @property integer $sex
 * @property string $phone
 * @property string $user_name
 * @property string $reg_date
 * @property integer $yuyue_time
 */
class UserYuyue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_yuyue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sex', 'yuyue_time'], 'integer'],
            [['child_name', 'birthday', 'reg_date'], 'string', 'max' => 100],
            [['phone', 'user_name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'child_name' => 'Child Name',
            'birthday' => 'Birthday',
            'sex' => 'Sex',
            'phone' => 'Phone',
            'user_name' => 'User Name',
            'reg_date' => 'Reg Date',
            'yuyue_time' => 'Yuyue Time',
        ];
    }
}
