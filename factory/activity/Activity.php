<?php
namespace app\factory\activity;
/**
 * Created by PhpStorm.
 * User: xj
 * Date: 2016/5/3
 * Time: 15:23
 * 活动接口
 */

interface Activity{
   public  function  createActivity($sql,$param);
   public  function  createActivityPage($sql,$sqlcount,$param,$pageSize);
}