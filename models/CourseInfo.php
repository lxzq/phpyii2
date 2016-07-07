<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_info".
 *
 * @property integer $id
 * @property integer $org_id
 * @property string $name
 * @property string $notes
 * @property string $logo
 * @property string $describe
 * @property string $status
 * @property string $class_time
 */
class CourseInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['org_id'], 'required'],
            [['id', 'org_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['logo'], 'string', 'max' => 150],
            [['describe'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'org_id' => 'Org ID',
            'name' => 'Name',
            'notes' => 'Notes',
            'logo' => 'Logo',
            'describe'=>'describe',
            'class_time'=>'class_time'
        ];
    }

    public function getOrg(){
        return  $this->hasOne(OrgInfo::className(),['id'=>'org_id']);
    }

    public function getTeacher(){
        return $this->hasMany(TeacherInfo::className(),['id'=>'teacher_id'])->viaTable(CourseTeacher::tableName(),['course_id'=>'id']);
    }

    public function getPrice(){
        return $this->hasMany(CourseManagerPrice::className(),['course_id'=>'id']);
    }

    public function getCourse(){
        return $this->hasMany(OrgShopInfo::className(),['org_id'=>'org_id']);
    }
}
