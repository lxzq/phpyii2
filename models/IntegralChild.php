<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "integral_child".
 *
 * @property integer $id
 * @property integer $child_id
 * @property string $integral_name
 * @property integer $integral_val
 * @property string $integral_date
 * @property string $integral_code
 */
class IntegralChild extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'integral_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['child_id', 'integral_val'], 'integer'],
            [['integral_date'], 'safe'],
            [['integral_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'child_id' => '孩子会员ID',
            'integral_name' => '积分名称',
            'integral_val' => '积分值',
            'integral_date' => '积分日期',
            'integral_code'=>'积分编码'
        ];
    }

    public function getChild(){
        return $this->hasOne(ChildInfo::className(),['id'=>'child_id']);
    }
}
