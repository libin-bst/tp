<?php
namespace app\message\controller;

use think\Request;
use \think\Controller;
use app\services\MessageService;
use app\sdk\MessageSdk;

class Message extends Controller
{
    protected $messageService;
    protected $messageSdk;

    public function __construct()
    {
        parent::__construct();
        $this->messageService=new MessageService();
        $this->messageSdk=new MessageSdk();
    }

    /**
     * 消息获取
     */
    public function index(Request $request)
    {
        $paramsObj=paramsObj();
        $paramsObj->mixed=1;
        $checkResult=$this->messageService->listData($paramsObj);
        $result=$checkResult["result"];

        // 模板变量赋值
        $this->assign('param',$request->param());
        $this->assign('result',$result);

        // 模板输出
        return $this->fetch('index');
    }

    /**
     * 查询消息详情
     */
    public function info(Request $request)
    {
        $param=$request->param();

        $paramsObj=paramsObj();
        $paramsObj->mixed=1;
        $checkResult=$this->messageService->getInfo($paramsObj);
        $infoResult=$checkResult["result"];

        $paramsObj=paramsObj();
        $paramsObj->params=array(
            "messageId"=>$param["messageId"],
            "recId"=>$param["recId"],
        );
        $this->messageService->readData($paramsObj);

        // 模板变量赋值
        $this->assign('param',$param);
        $this->assign('messageData',$infoResult);
        // 模板输出
        return $this->fetch('info');
    }

}
