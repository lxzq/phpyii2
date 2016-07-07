<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_visit".
 *
 * @property integer $id
 * @property integer $cusromer_record_id
 * @property string $content
 * @property string $notes
 * @property string $add_time
 * @property string $user
 */
class CustomerVisit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_visit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cusromer_record_id'], 'required'],
            [['cusromer_record_id'], 'integer'],
            [['add_time'], 'safe'],
            [['content', 'notes', 'user'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cusromer_record_id' => 'Cusromer Record ID',
            'content' => 'Content',
            'notes' => 'Notes',
            'add_time' => 'Add Time',
            'user' => 'User',
        ];
    }

    public function getVisitor(){
        return $this->hasOne(CustomerRecord::className(),['id'=>'cusromer_record_id']);
    }
}
