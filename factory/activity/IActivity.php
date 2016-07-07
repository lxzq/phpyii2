<?php
namespace app\factory\activity;
use Yii;
use app\factory\activity\Activity;
use yii\data\SqlDataProvider;
use yii\data\Pagination;

/**
 * Created by PhpStorm.
 * User: xj
 * Date: 2016/5/3
 * Time: 15:28
 * 创建艺术大赛活动实现类
 */

class IActivity implements Activity{

    /**
     * 获取活动数据
     * @return array
     */
    function  createActivity($sql,$param)
    {
        $connection  = Yii::$app->db;
        $command = $connection->createCommand($sql);
        if(!empty($param)){
            foreach($param as $index=>$value){
                $command->bindValue($index, $value);
            }
        }
        $list=$command->queryAll();
        return $list;
    }
    /**
     * 获取活动分页
     * @return array
     */

    public function  createActivityPage($sql,$sqlcount,$param,$pagesize)
    {

        $connection  = Yii::$app->db;
        $command = $connection->createCommand($sqlcount);
        if(!empty($param)){
            foreach($param as $index=>$value){
                $command->bindValue($index, $value);
            }
        }
        $count=$command->queryScalar();
        $pages = new Pagination([
            'totalCount'=>$count,
            'pageSize'=>$pagesize
        ]);
        $pages->getPage();
        $result= new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'params' => $param,
            'pagination' =>$pages,
        ]);
        return $result;
    }
}