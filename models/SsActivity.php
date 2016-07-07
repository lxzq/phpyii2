<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_activity".
 *
 * @property integer $activity_id
 * @property string $activity_name
 * @property string $activity_centent
 * @property string $intro
 * @property string $activity_image
 * @property integer $activity_createtime
 * @property integer $activity_starttime
 * @property integer $activity_endtime
 * @property integer $activity_sort
 * @property string $lunbo
 * @property string $activity_theme
 * @property integer $activity_number
 * @property integer $activity_user
 * @property integer $activity_comment
 * @property integer $activity_collect
 * @property integer $activity_forwarding
 * @property string $activity_address
 * @property string $activity_host
 * @property string $activity_second
 * @property string $activity_tel
 * @property string $activity_mobile
 */
class SsActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_name', 'activity_image', 'intro'], 'required'],
            [['activity_centent'], 'string'],
            [['activity_createtime', 'activity_starttime', 'activity_endtime'], 'safe'],
            [['activity_name'], 'string', 'max' => 100],
            [['activity_image'], 'string', 'max' => 500],
            [['intro'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_id' => '活动ID',
            'activity_name' => '活动名称',
            'activity_centent' => '活动内容',
            'activity_image' => '活动图片',
            'activity_createtime' => '活动创建时间',
            'activity_starttime' => '活动开始时间',
            'activity_endtime' => '活动截止时间',
            'activity_theme'=>'活动主题',
            'activity_number'=>'活动人数',
            'activity_address'=>'活动地址',
            'activity_host'=>'主办单位',
            'activity_tel'=>'活动热线'
        ];
    }

    public function getAuser(){
            return $this->hasMany(SsActivityUser::className(),['activity_id'=>'activity_id']);
    }
}
