<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_activity_user".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property string $nickname
 * @property string $phone
 * @property string $face
 * @property string $add_time
 * @property string $open_id
 * @property integer $status
 * @property string $child_id
 */
class SsActivityUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_activity_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           [['activity_id', 'add_time', 'status'], 'integer'],
            [['nickname', 'child_id'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 20],
            [['face'], 'string', 'max' => 255],
            [['open_id'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键ID',
            'activity_id' => '活动id',
            'nickname' => '报名人昵称',
            'phone' => '报名人电话',
            'face' => '报名人头像',
            'add_time' => '报名时间',
            'status' => '状态1已报名2未报名',
            'open_id' => '微信openID',
            'child_id' => '孩子id',
        ];
    }

    public function getActivity(){
        return $this->hasOne(SsActivity::className(),['activity_id'=>'activity_id']);
    }
}

