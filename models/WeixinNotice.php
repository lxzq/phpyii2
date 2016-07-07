<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_weixin_notice".
 *
 * @property integer $id
 * @property integer $template_id
 * @property string $send_scope
 * @property string $content
 * @property string $url
 * @property integer $send_time
 * @property integer $add_time
 * @property integer $change_time
 */
class WeixinNotice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_weixin_notice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id','send_time','add_time','change_time'], 'integer'],
            [['content','url'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'template_id' => '模板id',
            'content' => '内容',
        ];
    }
    /**
     * 关联推送模板表
     */
    public function getTemplate()
    {
        return $this->hasOne(PushTemplate::className(),['id'=>'template_id']);
    }
}
