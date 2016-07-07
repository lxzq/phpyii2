<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/1
 * Time: 11:59
 */

namespace app\modules\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\factory\activity\IActivityFactory;
use app\factory\activity\IActivityPoxy;

class ArtController  extends  Controller
{

    public $layout  = 'layout';

    /**
     * accesscontrol
     */

    /**
     * @用户授权规则
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 获取列表
     */
    public function actionList(){

        //获取活动工厂
        $factory=new IActivityFactory();
        $phone="";
        $sqlcount="SELECT count(1)
  FROM art_contest
       LEFT JOIN art_dictionaries
          ON art_dictionaries.id = art_contest.project";
        $sql="SELECT art_contest.id,
       art_contest.name,
       age,
       sex,
       phone,
       project,
       remarks,
       img,
       resume,
       createtime,
       art_dictionaries.name as projectname
  FROM art_contest
       LEFT JOIN art_dictionaries
          ON art_dictionaries.id = art_contest.project";
        if(!empty($_REQUEST["phone"])){
            $sql.="\n where art_contest.phone=:phone";
            $sqlcount.="\n where art_contest.phone=:phone";
            $list= $factory->getActivityPage($sql,$sqlcount,[":phone"=>$_REQUEST["phone"]],8);
            //$list->getPagination()->params=['phone' =>$_REQUEST["phone"]];
        }else{
            $list= $factory->getActivityPage($sql,$sqlcount,null,8);
        }
        return $this->render("artlist",["data"=>$list->getModels(),"pages"=>$list->getPagination()]);
    }

}