<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_child_info".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $child_id
 * @property integer $relation
 * @property integer $isNurse
 */
class UserChildInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_child_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'child_id', 'relation', 'isNurse'], 'integer']
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
            'child_id' => 'Child ID',
            'relation' => 'Relation',
            'isNurse' => 'Is Nurse',
        ];
    }
}
