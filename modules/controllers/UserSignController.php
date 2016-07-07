<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-06-07
 * Time: 10:11
 */

namespace app\modules\controllers;


use app\models\UserSign;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\ShopInfo;
use yii\data\Pagination;
class UserSignController extends Controller
{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout = 'layout';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

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
                        'actions' => ['login', 'captcha'],
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

    public function actionList(){

        $where = [];
        $params = [];
        $shopId = 0;
        $nickname = '';
        $shops = ShopInfo::find()->where(['>','dept_id',0])->all();
        if(!empty($_REQUEST["shop"])){
            $shopId = $_REQUEST["shop"];
            $where['user.shopId'] =$shopId;
            $params['shop'] = $shopId;
        }
        if(!empty($_REQUEST['nickname'])){
            $nickname = $_REQUEST['nickname'];
            $where['user.nickname'] =$nickname;
            $params['nickname'] = $nickname;
        }

        $list = UserSign::find()->joinWith('user')->where($where)->orderBy('user_sign.add_time desc');
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        $pages->params = $params;
       return $this->renderPartial('list',['pages'=>$pages ,'data'=>$data ,'shops'=>$shops,'shopId'=>$shopId,'nickname'=>$nickname]);
    }
}