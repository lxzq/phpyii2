<?php

namespace app\modules\weixin;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\ActiveRecord;
use yii\base\Model;


class BaseModel extends Model
{

	public $isNewRecord;
}
