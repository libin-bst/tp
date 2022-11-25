<?php
namespace app\user\controller;

use think\Db;

class User
{
   public function index()
   {
       $result=Db::table('users')->where('guid',"F0012E92C11537D671BCF209F826A92F")->find();
       var_dump($result);
   }
}
