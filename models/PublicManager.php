<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "public_manager".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $public_id
 * @property integer $is_creator
 */
class PublicManager extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'public_manager';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'public_id', 'is_creator'], 'integer'],
            [['user_id', 'public_id'], 'unique', 'targetAttribute' => ['user_id', 'public_id'], 'message' => 'The combination of User ID and Public ID has already been taken.']
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
            'public_id' => 'Public ID',
            'is_creator' => 'Is Creator',
        ];
    }
}
