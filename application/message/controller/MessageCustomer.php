<?php
namespace app\message\controller;

use think\Request;
use think\Db;
use \think\Controller;

class MessageCustomer extends Controller
{
    /**
     * 消息获取
     */
    public function index(Request $request)
    {
        $param=$request->param();
        $recId=$param["recId"];

        $result=Db::table('message')->alias('m')->join('message_customer mc','mc.messageId = m.messageId and mc.customerId='.$recId,"LEFT")->where('m.recId',"0")->whereOr("m.recId",$recId)->order("m.messageId","desc")->field("m.*,mc.status")->select();

        // 模板变量赋值
        $this->assign('recId',$recId);
        $this->assign('result',$result);

        // 模板输出
        return $this->fetch('index');
    }

    /**
     * 读取消息
     */
    public function read(Request $request)
    {
        $param=$request->param();
        $recId=$param["recId"];
        $messageId=$param["messageId"];

        echo "{$recId}:读取 {$messageId}消息<br />";

        $result=Db::table('message_customer')->where('customerId',$recId)->where('messageId',$messageId)->find();
        if(!$result){
            $data = [];
            $data["customerId"]=$recId;
            $data["messageId"]=$messageId;
            $data["status"]=1;
            Db::table('message_customer')->insert($data);
            echo "阅读成功";
        }else{
            echo "已阅读";
        }
    }

    /**
     * 获取信息
     */
    public function getData(Request $request)
    {
        $param=$request->param();
        $recId=$param["recId"];
        $messageId=$param["messageId"];
        $result=Db::table('message')->alias('m')->join('message_customer mc','mc.messageId = m.messageId and mc.customerId='.$recId,"LEFT")->where("m.recId=0 or m.recId={$recId}")->where("m.messageId > {$messageId}")->order("m.messageId","asc")->field("m.*,IFNULL(mc.status,0) as status")->select();

        header('content-type:application/json;charset=utf-8');
        return  apiResult(true,"success",0,array("dataType"=>1,"data"=>$result));
    }
}
