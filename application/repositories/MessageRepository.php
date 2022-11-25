<?php

namespace app\repositories;

use app\common\Cparam_parse;
use app\common\Cverify_param;
use think\Db;
use app\common\Error_level;
use app\common\Error_code;

class MessageRepository{

    /**
     * 获取消息列表
     */
    public function listData($paramObj=""){
        $dataObj=paramsObj();
        $dataObj->request=paramsObj();
        $dataObj->data=paramsObj();
        $dataObj->data->use_trans=false;
        $dataObj->result=array();

        $final_arr=logInit();
        if($final_arr["error_no"]==0){
            $mixed=Cparam_parse::parseToArr($paramObj,"mixed",0);
            $params=Cparam_parse::parseToArr($paramObj,"params",array());
            $rules=array(
                'recId' => 'require',
            );
            $paramsObj=paramsObj();
            $paramsObj->params=$params;
            $paramsObj->mixed=$mixed;
            $paramsObj->rules=$rules;
            $checkResult = Cverify_param::validatorRequest($paramsObj);
            if($checkResult["error_no"]==0){
                $dataObj->request=$checkResult["result"];
            }else{
                $final_arr=logCallErrorMsg($final_arr,$checkResult);
            }
        }
        if($final_arr["error_no"]==0){
            try{
                $result=Db::table('message')->alias('m')->join('message_customer mc','mc.messageId = m.messageId and mc.customerId='.$dataObj->request->recId,"LEFT")->where('m.recId',"0")->whereOr("m.recId",$dataObj->request->recId)->order("m.messageId","desc")->field("m.*,IFNULL(mc.status,0) as status")->select();
                $final_arr["result"]=$result;
            }catch (\Exception $exception){
                $logMsg="code:".$exception->getCode().",message:".$exception->getMessage();
                $final_arr=recordLogMsg(Error_level::E_WARNING,Error_code::SQL_ERROR,"sql错误",$logMsg);
            }
        }
        return $final_arr;
    }

    /**
     * 获取消息详情
     */
    public function getInfo($paramObj=""){
        $dataObj=paramsObj();
        $dataObj->request=paramsObj();
        $dataObj->data=paramsObj();
        $dataObj->data->use_trans=false;
        $dataObj->result=array();

        $final_arr=logInit();
        if($final_arr["error_no"]==0){
            $mixed=Cparam_parse::parseToArr($paramObj,"mixed",0);
            $params=Cparam_parse::parseToArr($paramObj,"params",array());
            $rules=array(
                'messageId' => 'require',
            );
            $paramsObj=paramsObj();
            $paramsObj->params=$params;
            $paramsObj->mixed=$mixed;
            $paramsObj->rules=$rules;
            $checkResult = Cverify_param::validatorRequest($paramsObj);
            if($checkResult["error_no"]==0){
                $dataObj->request=$checkResult["result"];
            }else{
                $final_arr=logCallErrorMsg($final_arr,$checkResult);
            }
        }
        if($final_arr["error_no"]==0){
            try{
                $messageResult=Db::table('message')->where('messageId',$dataObj->request->messageId)->field("title")->find();
                $messageData=[];
                if(!empty($messageResult)){
                    $messageTextResult=Db::table('message_text')->where('messageId',$dataObj->request->messageId)->field("message_content,create_date")->find();
                    $messageData["title"]=$messageResult["title"];
                    $messageData["content"]=$messageTextResult["message_content"];
                    $messageData["create_date"]=$messageTextResult["create_date"];
                }
                $final_arr["result"]=$messageData;
            }catch (\Exception $exception){
                $logMsg="code:".$exception->getCode().",message:".$exception->getMessage();
                $final_arr=recordLogMsg(Error_level::E_WARNING,Error_code::SQL_ERROR,"sql错误",$logMsg);
            }
        }
        return $final_arr;
    }

    /**
     * 阅读数据
     */
    public function readData($paramObj=""){
        $dataObj=paramsObj();
        $dataObj->request=paramsObj();
        $dataObj->data=paramsObj();
        $dataObj->data->use_trans=false;
        $dataObj->result=array();

        $final_arr=logInit();
        if($final_arr["error_no"]==0){
            $mixed=Cparam_parse::parseToArr($paramObj,"mixed",0);
            $params=Cparam_parse::parseToArr($paramObj,"params",array());
            $rules=array(
                'messageId' => 'require',
                'recId' => 'require',
            );
            $paramsObj=paramsObj();
            $paramsObj->params=$params;
            $paramsObj->mixed=$mixed;
            $paramsObj->rules=$rules;
            $checkResult = Cverify_param::validatorRequest($paramsObj);
            if($checkResult["error_no"]==0){
                $dataObj->request=$checkResult["result"];
            }else{
                $final_arr=logCallErrorMsg($final_arr,$checkResult);
            }
        }
        if($final_arr["error_no"]==0){
            try{
                //判断消息是否存在，以及是否属于自己的消息。
                $messageResult=Db::table('message')->where('recId=0 or recId='.$dataObj->request->recId)->whereOr('messageId',$dataObj->request->messageId)->field("messageId")->find();
                if($messageResult){
                    //判断消息是否存在
                    $result=Db::table('message_customer')->where('customerId',$dataObj->request->recId)->where('messageId',$dataObj->request->messageId)->find();
                    if(!$result){
                        $data = [];
                        $data["customerId"]=$dataObj->request->recId;
                        $data["messageId"]=$dataObj->request->messageId;
                        $data["status"]=1;
                        Db::table('message_customer')->insert($data);
                    }
                }
            }catch (\Exception $exception){
                $logMsg="code:".$exception->getCode().",message:".$exception->getMessage();
                $final_arr=recordLogMsg(Error_level::E_WARNING,Error_code::SQL_ERROR,"sql错误",$logMsg);
            }
        }
        return $final_arr;
    }

    /**
     * 录入消息
     */
    public function store($paramObj=""){
        $dataObj=paramsObj();
        $dataObj->request=paramsObj();
        $dataObj->data=paramsObj();
        $dataObj->data->use_trans=false;
        $dataObj->result=array();

        $final_arr=logInit();
        if($final_arr["error_no"]==0){
            $mixed=Cparam_parse::parseToArr($paramObj,"mixed",0);
            $params=Cparam_parse::parseToArr($paramObj,"params",array());
            $rules=array(
                'sendId' => 'require',
                'recId' => 'require',
                'message_content' => 'require',
                'title' => 'require',
            );
            $paramsObj=paramsObj();
            $paramsObj->params=$params;
            $paramsObj->mixed=$mixed;
            $paramsObj->rules=$rules;
            $checkResult = Cverify_param::validatorRequest($paramsObj);
            if($checkResult["error_no"]==0){
                $dataObj->request=$checkResult["result"];
            }else{
                $final_arr=logCallErrorMsg($final_arr,$checkResult);
            }
        }
        if($final_arr["error_no"]==0){
            // 启动事务
            Db::startTrans();
            try{
                $data = [];
                $data["message_content"]=$dataObj->request->message_content;
                $data["create_date"]=date("Y-m-d H:i:s");
                $messageId=Db::table('message_text')->insertGetId($data);
                if($messageId){
                    $data = [];
                    $data["sendId"]=$dataObj->request->sendId;
                    $data["recId"]=$dataObj->request->recId;
                    $data["messageId"]=$messageId;
                    $data["title"]=$dataObj->request->title;
                    $data["create_date"]=date("Y-m-d H:i:s");
                    Db::table('message')->insert($data);
                    Db::commit();
                }else{
                    Db::rollback();
                }
            }catch (\Exception $exception){
                Db::rollback();
                $logMsg="code:".$exception->getCode().",message:".$exception->getMessage();
                $final_arr=recordLogMsg(Error_level::E_WARNING,Error_code::SQL_ERROR,"sql错误",$logMsg);
            }
        }
        return $final_arr;
    }

    /**
     * 获取未读数据
     */
    public function getUnreadData($paramObj=""){
        $dataObj=paramsObj();
        $dataObj->request=paramsObj();
        $dataObj->data=paramsObj();
        $dataObj->data->use_trans=false;
        $dataObj->result=array();

        $final_arr=logInit();
        if($final_arr["error_no"]==0){
            $mixed=Cparam_parse::parseToArr($paramObj,"mixed",0);
            $params=Cparam_parse::parseToArr($paramObj,"params",array());
            $rules=array(
                'recId' => 'require',
                'messageId' => 'require',
            );
            $paramsObj=paramsObj();
            $paramsObj->params=$params;
            $paramsObj->mixed=$mixed;
            $paramsObj->rules=$rules;
            $checkResult = Cverify_param::validatorRequest($paramsObj);
            if($checkResult["error_no"]==0){
                $dataObj->request=$checkResult["result"];
            }else{
                $final_arr=logCallErrorMsg($final_arr,$checkResult);
            }
        }
        if($final_arr["error_no"]==0){
            try{
                $result=Db::table('message')->alias('m')->join('message_customer mc','mc.messageId = m.messageId and mc.customerId='.$dataObj->request->recId,"LEFT")->where("m.recId=0 or m.recId={$dataObj->request->recId}")->where("m.messageId > {$dataObj->request->messageId}")->order("m.messageId","asc")->field("m.*,IFNULL(mc.status,0) as status")->select();
                $final_arr["result"]=$result;
            }catch (\Exception $exception){
                $logMsg="code:".$exception->getCode().",message:".$exception->getMessage();
                $final_arr=recordLogMsg(Error_level::E_WARNING,Error_code::SQL_ERROR,"sql错误",$logMsg);
            }
        }
        return $final_arr;
    }

    public function __call($name,$arguments)
    {
        return recordLogMsg(Error_level::E_WARNING,Error_code::UNDEFINED_METHOD,"未定义方法",$name,$arguments);
    }
}