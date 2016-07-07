<?php
namespace app\factory\activity;

/**
 * Created by PhpStorm.
 * User: xj
 * Date: 2016/5/3
 * Time: 15:43
 * 创建活动工厂接口
 */
interface ActivityFactory{
    function getActivity($sql,$param);
    function getActivityPage($sql,$sqlcount,$param,$pageSize);
}