<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_record".
 *
 * @property integer $id
 * @property string $name
 * @property string $tel
 * @property string $content
 * @property string $add_date
 * @property integer $shop_id
 * @property string $add_user
 * @property string $notes
 * @property string $sex
 * @property string $birth_date
 * @property string $child_class
 */
class CustomerRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_date'], 'safe'],
            [['shop_id','add_user'], 'integer'],
            [['name', 'child_class'], 'string', 'max' => 50],
            [['tel'], 'string', 'max' => 20],
            [['content', 'notes'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'tel' => 'Tel',
            'content' => 'Content',
            'add_date' => 'Add Date',
            'shop_id' => 'Shop ID',
            'add_user' => 'Add User',
            'notes' => 'Notes',
            'sex'=>'sex',
            'birth_date'=>'birth_date',
            'child_class'=>'child_class'
        ];
    }

    public function getUser(){
        return $this->hasOne(User::className(),["id"=>"add_user"]);
    }
}
