<?php
namespace app\message\controller;

use think\Db;
use think\Request;
use \think\Controller;

class Message extends Controller
{
    /**
     * 录入消息
     */
    public function store(Request $request)
    {
        $isSuccess=0;
        // 启动事务
        Db::startTrans();
        try{
            $param=$request->param();
            $data = [];
            $data["message_content"]=$param["message_content"];
            $data["create_date"]=date("Y-m-d H:i:s");
            $messageId=Db::table('message_text')->insertGetId($data);
            if($messageId){
                $data = [];
                $data["sendId"]=$param["sendId"];
                $data["recId"]=$param["recId"];
                $data["messageId"]=$messageId;
                $data["title"]=$param["title"];
                $data["create_date"]=date("Y-m-d H:i:s");
                Db::table('message')->insert($data);
                Db::commit();
                $isSuccess=1;
            }else{
                Db::rollback();
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        if($isSuccess==1){
            echo "录入成功";
        }else{
            echo "录入失败";
        }
    }

    /**
     * 查询消息详情
     */
    public function info(Request $request)
    {
        $param=$request->param();

        $messageTextResult=Db::table('message_text')->where('messageId',$param["messageId"])->field("message_content,create_date")->find();
        $messageResult=Db::table('message')->where('messageId',$param["messageId"])->field("title")->find();

        $messageData=[];
        $messageData["title"]=$messageResult["title"];
        $messageData["content"]=$messageTextResult["message_content"];
        $messageData["create_date"]=$messageTextResult["create_date"];

        // 模板变量赋值
        $this->assign('recId',$param["recId"]);
        $this->assign('messageId',$param["messageId"]);
        $this->assign('messageData',$messageData);
        //查询成功->更新读取信息
        if(isset($messageResult["title"])){
            $result=Db::table('message_customer')->where('customerId',$param["recId"])->where('messageId',$param["messageId"])->find();
            if(!$result){
                $data = [];
                $data["customerId"]=$param["recId"];
                $data["messageId"]=$param["messageId"];
                $data["status"]=1;
                Db::table('message_customer')->insert($data);
            }
        }
        // 模板输出
        return $this->fetch('index');
    }

}
