<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-19
 * Time: 17:27
 */

namespace app\modules\controllers;


use app\models\OtherMoneyRecord;
use app\models\OtherMoneyRecordForm;
use app\models\ShopInfo;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use Yii;


class OthermoneyController extends Controller
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
                        'actions' => ['list','add','save','del'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionList(){
        $where = [];
        $shopId = Yii::$app->user->identity->shopId;
        if(!empty($shopId)){
            $where["shop_id"] = $shopId;
        }
        $name = [];
        if(!empty($_REQUEST["name"])){
            $name = ['like','pay_name',$_REQUEST["name"]];
        }
        $params = [];
        $startTime =[];
        $starttime = '';
        if(!empty($_REQUEST["startTime"])){
            $startTime = ['>=' ,'add_date',$_REQUEST["startTime"]];
            $starttime = $_REQUEST["startTime"];
            $params["startTime"] = $_REQUEST["startTime"];
        }
        $endTime=[];
        $endtime = '';
        if(!empty($_REQUEST["endTime"])){
            $endTime = ['<=' ,'add_date',$_REQUEST["endTime"]];
            $endtime = $_REQUEST["endTime"];
            $params["endTime"] = $_REQUEST["endTime"];
        }
        $list = OtherMoneyRecord::find()->where($where)->andWhere($name)->andWhere($startTime)->andWhere($endTime)->with('shop')->with('user')->orderBy("add_date desc");
        $pages = new Pagination(['totalCount' => $list->count(),'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        $pages->params = $params;

        //合计
        $connection = Yii::$app->db;
        $sql = "SELECT sum(pay_money) as total FROM other_money_record where shop_id = :shopId ";
        if(!empty($_REQUEST["startTime"])){
            $sql .= ' and add_date >= :startTime';
        }
        if(!empty($_REQUEST["endTime"])){
            $sql .= ' and add_date <= :endTime';
        }
        $command = $connection->createCommand($sql);
        $command->bindValue(':shopId', $shopId);
        if(!empty($_REQUEST["startTime"])){
            $command->bindValue(':startTime', $starttime);
        }
        if(!empty($_REQUEST["endTime"])){
            $command->bindValue(':endTime', $endtime);
        }
        $total = $command->queryAll();

        return $this->render('list',[
            'list' => $data,
            'pages' => $pages,
            'startTime'=>$starttime,
            'endTime'=>$endtime,
            'total'=>$total
        ]);
    }

    public function actionAdd(){
        $model = new OtherMoneyRecordForm();
        $where = [];
        $shopId = Yii::$app->user->identity->shopId;
        if (!empty($shopId)) {
            $where['id'] = $shopId;
        }
        $shops = ShopInfo::find()->where($where)->all();
        $shopsList = [];
        foreach ($shops as $shop) {
            $shopsList[$shop["id"]] = $shop["name"];
        }
        $model->payType = 1;
        $model->addDate = date('Y-m-d', time());
        $model->payName = '散客';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->goBack();
        } else {
            return $this->render('add', [
                'model' => $model,
                'shops' => $shopsList
            ]);
        }
    }

    public function actionSave(){
        $params = $_REQUEST["OtherMoneyRecordForm"];
        $addDate = $_REQUEST["addDate"];
        $data = new OtherMoneyRecord();
        $data->check_status = 0;
        $data->add_date = $addDate;
        $data->pay_money = $params["payMoney"];
        $data->pay_type = $params["payType"];
        $data->shop_id = $params["shopId"];
        $data->yii_user_id = Yii::$app->user->identity->id;
        $data->pay_name = $params["payName"];
        $data->receipt_id = $params["receiptId"];
        $data->notes = $params['notes'];
        $data->save();

       /* if($params["payType"] == 1){//现金
            $shopCash = new ShopCashRecord();
            $shopCash->add_time = date('Y-m-d H:i:s', time());
            $shopCash->is_finance = 1;
            $shopCash->total_money = $params["payMoney"];
            $shopCash->shop_id = $params["shopId"];
            $shopCash->type = 2;
            $shopCash->user_id = Yii::$app->user->identity->id;
            if(!$shopCash->save()){
                throw new \Exception();
            }
        }*/

        return $this->redirect("/admin/othermoney/list");
    }

    public function actionDel(){
        $id = $_REQUEST["id"];
        OtherMoneyRecord::findOne($id)->delete();
        return $this->redirect("/admin/othermoney/list");
    }
}