<?php

namespace app\services;

use app\repositories\MessageRepository;
use app\common\Error_level;
use app\common\Error_code;

class MessageService{
    protected $messageRepository;

    public function __construct()
    {
        $this->messageRepository=new MessageRepository();
    }

    /**
     * 获取消息列表
     * @param string $paramObj
     * @return mixed
     */
    public function listData($paramObj="")
    {
        return $this->messageRepository->listData($paramObj);
    }

    /**
     * 获取消息详情
     * @param string $paramObj
     * @return mixed
     */
    public function getInfo($paramObj="")
    {
        return $this->messageRepository->getInfo($paramObj);
    }

    /**
     * 阅读数据
     * @param string $paramObj
     * @return mixed
     */
    public function readData($paramObj="")
    {
        return $this->messageRepository->readData($paramObj);
    }

    /**
     * 录入消息
     * @param string $paramObj
     * @return mixed
     */
    public function store($paramObj="")
    {
        return $this->messageRepository->store($paramObj);
    }

    /**
     * 获取未读数据
     * @param string $paramObj
     * @return mixed
     */
    public function getUnreadData($paramObj="")
    {
        return $this->messageRepository->getUnreadData($paramObj);
    }

    public function __call($name,$arguments)
    {
        return recordLogMsg(Error_level::E_WARNING,Error_code::UNDEFINED_METHOD,"未定义方法",$name,$arguments);
    }
}