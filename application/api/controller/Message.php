<?php
namespace app\api\controller;

use app\common\Error_code;
use think\Request;
use \think\Controller;
use app\services\MessageService;
use app\common\Sign_md5;

class Message extends Controller
{
    protected $messageService;

    public function __construct()
    {
        parent::__construct();
        //验证签名
        $this->signMiddleware();
        $this->messageService=new MessageService();
    }

    /**
     * 签名中间件
     */
    public function signMiddleware(){
        $request=request();
        $sign=new Sign_md5();
        if(!$sign->verifySign($request->param(),"asdasd1233123asd13123")){
            echo  apiResult(false,"sign fail",Error_code::SIGN_ERROR);
            exit;
        }
    }

    /**
     * 获取消息列表
     */
    public function listData(Request $request)
    {
        $paramsObj=paramsObj();
        $paramsObj->mixed=1;
        $checkResult=$this->messageService->listData($paramsObj);
        if($checkResult["error_no"]==0){
            return apiResult(true,"success",0,$checkResult["result"]);
        }else{
            return apiResult(false,$checkResult["error_msg"],$checkResult["error_no"]);
        }
    }

    /**
     * 阅读数据
     */
    public function readData(Request $request)
    {
        $paramsObj=paramsObj();
        $paramsObj->mixed=1;
        $checkResult=$this->messageService->readData($paramsObj);
        if($checkResult["error_no"]==0){
            return apiResult(true,"success",0,$checkResult["result"]);
        }else{
            return apiResult(false,$checkResult["error_msg"],$checkResult["error_no"]);
        }
    }

    /**
     * 获取消息详情
     */
    public function getInfo(Request $request)
    {
        $paramsObj=paramsObj();
        $paramsObj->mixed=1;
        $checkResult=$this->messageService->getInfo($paramsObj);
        if($checkResult["error_no"]==0){
            return apiResult(true,"success",0,$checkResult["result"]);
        }else{
            return apiResult(false,$checkResult["error_msg"],$checkResult["error_no"]);
        }
    }

    /**
     * 录入消息
     */
    public function store(Request $request)
    {
        $paramsObj=paramsObj();
        $paramsObj->mixed=1;
        $checkResult=$this->messageService->store($paramsObj);
        if($checkResult["error_no"]==0){
            return apiResult(true,"success",0,$checkResult["result"]);
        }else{
            return apiResult(false,$checkResult["error_msg"],$checkResult["error_no"]);
        }
    }

    /**
     * 获取未读信息
     */
    public function getUnreadData(Request $request)
    {
        $paramsObj=paramsObj();
        $paramsObj->mixed=1;
        $checkResult=$this->messageService->getUnreadData($paramsObj);
        if($checkResult["error_no"]==0){
            return apiResult(true,"success",0,$checkResult["result"]);
        }else{
            return apiResult(false,$checkResult["error_msg"],$checkResult["error_no"]);
        }
    }

}
