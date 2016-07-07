<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_activity_user_friend".
 *
 * @property integer $id
 * @property integer $activity_user_id
 * @property string $add_time
 * @property string $phone
 * @property string $nick_name
 */
class SsActivityUserFriend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_activity_user_friend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_user_id', 'add_time'], 'required'],
            [['activity_user_id'], 'integer'],
            [['add_time'], 'safe'],
            [['phone', 'nick_name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_user_id' => 'Activity User ID',
            'add_time' => 'Add Time',
            'phone' => 'Phone',
            'nick_name' => 'Nick Name',
        ];
    }
}
