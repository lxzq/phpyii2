<?php
namespace app\factory\activity;
use app\factory\activity\IActivity;
use app\factory\activity\ActivityFactory;
/**
 * Created by PhpStorm.
 * User: xj
 * Date: 2016/5/3
 * Time: 15:48
 *艺术大赛工厂类
 */
class IActivityFactory implements ActivityFactory{

    /**
     * 获取活动
     * @param $sql
     * @return array
     */
    function getActivity($sql,$param)
    {
        $iActivity= new IActivity();
        //创建活动
        return $iActivity->createActivity($sql,$param);
    }
    /**
     * 获取活动分页
     * @param $sql
     * @return array
     */

    function getActivityPage($sql,$sqlcount, $param, $pageSize)
    {
        $iActivity= new IActivity();
        return $iActivity->createActivityPage($sql, $sqlcount,$param, $pageSize);
    }
}