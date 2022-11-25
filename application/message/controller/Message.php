<?php
namespace app\message\controller;

use think\Request;
use \think\Controller;
use app\sdk\MessageSdk;

class Message extends Controller
{
    protected $messageSdk;

    public function __construct()
    {
        parent::__construct();
        $this->messageSdk=new MessageSdk();
    }

    /**
     * 消息获取
     */
    public function index(Request $request)
    {
        $result=$this->messageSdk->listData($request->param("recId"));
        // 模板变量赋值
        $this->assign('param',$request->param());
        $this->assign('result',$result["data"]);

        // 模板输出
        return $this->fetch('index');
    }

    /**
     * 查询消息详情
     */
    public function info(Request $request)
    {
        $param=$request->param();
        $result=$this->messageSdk->getInfo($param["recId"],$param["messageId"]);
        $this->messageSdk->readData($param["recId"],$param["messageId"]);
        // 模板变量赋值
        $this->assign('param',$param);
        $this->assign('messageData',$result["data"]);
        // 模板输出
        return $this->fetch('info');
    }

}
