<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-19
 * Time: 9:48
 */

namespace app\modules\controllers;


use app\models\IntegralChild;
use app\models\IntegralManager;
use app\models\IntegralManagerForm;
use app\models\ShopInfo;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use Yii;

/**
 * 积分管理
 * Class IntegralController
 * @package app\modules\controllers
 */
class IntegralController extends Controller
{

    public $layout  = 'layout';
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
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['list','edit','save','integral-list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionList(){
        $list = IntegralManager::find()->orderBy("code");
        $pages = new Pagination(['totalCount' => $list->count(),'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('list',[
            'list' => $data,
            'pages' => $pages
        ]);
    }

    public function actionEdit(){
        $id = $_REQUEST["id"];
        $data = IntegralManager::findOne($id);
        $model = new IntegralManagerForm();
        $model->id = $data->id;
        $model->name = $data->name;
        $model->opVal = $data->op_val;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->goBack();
        } else {
            return $this->render('add',[
                'model' => $model
            ]);
        }
    }

    public function actionSave(){
        $params = $_REQUEST["IntegralManagerForm"];
        $data = IntegralManager::findOne($_REQUEST["id"]);
        $data->op_val = $params["opVal"];
        $data->save();
        return $this->redirect("/admin/integral/list");
    }

    /**
     * 积分记录查询
     */
    public function actionIntegralList(){
        $where = [];
        $shopId = Yii::$app->user->identity->shopId;
        if(!empty($_REQUEST["name"])){
            $where = ['like','child_info.nick_name',$_REQUEST["name"]];
        }
        $sql = [];
        if(!empty($shopId)){
            $sql["child_info.shop_id"] = $shopId;
        }

        $shops = ShopInfo::find()->where(['>','dept_id',0])->all();

        $list = IntegralChild::find()->joinWith('child')->where($sql)->andWhere($where)->orderBy("integral_child.integral_date desc ");
        $pages = new Pagination(['totalCount' => $list->count(),'pageSize' => '10']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('integrallist',[
            'list' => $data,
            'pages' => $pages,
            'shops' => $shops
        ]);
    }

    /**
     * 会员积分
     * @param $childId
     * @param $code
     * @param $val
     * @param $msg 描述
     */
    public static function addIntegral($childId,$code,$val,$msg){
       if(2000 == $code){//表示会员消耗积分
            $integralChild = new IntegralChild();
            $integralChild->child_id = $childId;
            $integralChild->integral_name =$msg;
            $integralChild->integral_date = date('Y-m-d', time());
            $integralChild->integral_val = -$val;
            $integralChild->integral_code = $code;
            return  $integralChild->save();
        }

        $codeData = IntegralManager::find()->where(['=','code',$code])->all();
        if(!empty($codeData)){
            $integralVal = $codeData[0]["op_val"] ;
            if($code == 1002 ||$code == 2001){//表示申报课程运算积分
                $integralVal = $val * $codeData[0]["op_val"];
            }
            $integralChild = new IntegralChild();
            $integralChild->child_id = $childId;
            $integralChild->integral_name = $codeData[0]["name"];
            $integralChild->integral_date = date('Y-m-d', time());
            $integralChild->integral_code = $code;
            if($code < 2000){//表示增加积分
                $integralChild->integral_val = $integralVal;
            }else {//表示扣除积分
                $integralChild->integral_val = -$integralVal;
            }
            return $integralChild->save();
        }else {
            return false;
        }
    }

}