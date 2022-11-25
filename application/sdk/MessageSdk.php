<?php

namespace app\sdk;

class MessageSdk
{
    public $signKey="asdasd1233123asd13123";
    public $url="http://www.tp5.com";

    /**
     * 获取未读信息
     */
    public function getUnreadData($recId,$messageId){
        $tansData=array();
        $tansData["recId"]=$recId;
        $tansData["messageId"]=$messageId;
        $tansData=$this->paramSign($tansData);
        $submitUrl=$this->url."/api/message/getUnreadData";
        $result=$this->curlSubmit($submitUrl,$tansData,2,"get");
        return $result;
    }

    /**
     * 获取未读信息
     */
    public function getInfo($recId,$messageId){
        $tansData=array();
        $tansData["recId"]=$recId;
        $tansData["messageId"]=$messageId;
        $tansData=$this->paramSign($tansData);
        $submitUrl=$this->url."/api/message/getInfo";
        $result=$this->curlSubmit($submitUrl,$tansData,2,"get");
        return $result;
    }

    /**
     * 获取未读信息
     */
    public function listData($recId){
        $tansData=array();
        $tansData["recId"]=$recId;
        $tansData=$this->paramSign($tansData);
        $submitUrl=$this->url."/api/message/listData";
        $result=$this->curlSubmit($submitUrl,$tansData,2,"get");
        return $result;
    }

    /**
     * 阅读数据
     */
    public function readData($recId,$messageId){
        $tansData=array();
        $tansData["recId"]=$recId;
        $tansData["messageId"]=$messageId;
        $tansData=$this->paramSign($tansData);
        $submitUrl=$this->url."/api/message/readData";
        $result=$this->curlSubmit($submitUrl,$tansData,2,"put");
        return $result;
    }

    /**
     * 录入消息
     */
    public function store($sendId,$recId,$title,$message_content){
        $tansData=array();
        $tansData["recId"]=$recId;
        $tansData["title"]=$title;
        $tansData["sendId"]=$sendId;
        $tansData["message_content"]=$message_content;
        $tansData=$this->paramSign($tansData);
        $submitUrl=$this->url."/api/message/store";
        $result=$this->curlSubmit($submitUrl,$tansData,2,"post");
        return $result;
    }

    /**
     * 参数数据-增加签名
     */
    public function paramSign($data){
        $data["timestamp"]=time();
        $data["signature"]=$this->sign($data,$this->signKey);
        return $data;
    }

    /**
     * 签名算法MD5
     * @param $data
     * @param $appKey
     * @param string $str
     * @return string
     */
    public function sign($data,$appKey,&$str="")
    {
        if(isset($data['signature']))
        {
            unset($data['signature']);
        }
        foreach($data as $key=>$val)
        {
            $data[$key]=urldecode($val);
        }
        ksort($data);
        $str=implode("|",$data).'|'.$appKey;
        $sign=md5($str);
        return $sign;
    }

    /**
     * 检测公共字段
     * @param $data
     * @return bool
     */
    public  function checkCommonField($data,$appKey){
        if(!isset($data['timestamp'])){
            return false;
        }
        if(!isset($data['signature'])){
            return false;
        }
        if(!$appKey){
            return false;
        }
        return true;
    }

    /**
     * 验证数据：1、是否含有公共字段，2、签名是否正确，3、数据是否过期
     * @param $data
     * @param $appKey
     * @param string $str
     * @return bool
     */
    public function verifySign($data,$appKey,&$str=""){
        if($this->checkCommonField($data,$appKey)){
            $signature=$data['signature'];
            $sign=$this->sign($data,$appKey,$str);
            if(strcasecmp($signature,$sign)==0){
                return $this->checkValidity($data);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 检测数据是否过期
     * @param $data
     * @return bool
     */
    public function checkValidity($data){
        if(isset($data['timestamp'])){
            if(is_numeric($data['timestamp'])){
                $effectiveTime=$data['timestamp']+60; //有效时间一分钟
                $nowTime=time();
                if($nowTime > $effectiveTime){
                    return false;
                }else{
                    return true;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * curl 提交
     * @param $submitUrl
     * @param null $postData
     * @param string $method
     * @param int $timeout
     * @param array $headers
     */
    public function curlSubmit($submitUrl,$postData = null,$timeout=6,$method="post",$headers=array(),$isReturnHeader=0)
    {
        if(strtolower($method)=="get" && $postData && is_array($postData))
        {
            $submitUrl.='?'.http_build_query($postData);
        }
        $curl = curl_init($submitUrl);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        if ("https://" == substr($submitUrl,0,8))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        if($isReturnHeader)
        {
            curl_setopt($curl, CURLOPT_HEADER, true); // 过滤HTTP头
        }
        else
        {
            curl_setopt($curl, CURLOPT_HEADER, false); // 过滤HTTP头
        }
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        if($timeout)
        {
            curl_setopt($curl,CURLOPT_TIMEOUT, $timeout);// 单位 秒，超时限制。
        }
        // 是否不需要响应的正文,为了节省带宽及时间,在只需要响应头的情况下可以不要正文
        //curl_setopt($curl, CURLOPT_NOBODY, true);
        if(strtolower($method) != "get")
        {
            if(strtolower($method) == "post")
            {
                curl_setopt($curl,CURLOPT_POST,true); // post传输数据
            }
            if(is_array($postData)){
                curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($postData));// post传输数据
            }else{
                curl_setopt($curl,CURLOPT_POSTFIELDS,$postData);// post传输数据
            }
        }

        if($headers){
            curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
        }
        $sContent = curl_exec($curl);
        $errno=curl_errno($curl);
        $error_msg=curl_error($curl);

        $requestHeader=curl_getinfo($curl);
        $httpCode = curl_getinfo($curl,CURLINFO_HTTP_CODE); //获取响应状态码
        if($isReturnHeader)
        {
            $headerSize=curl_getinfo($curl,CURLINFO_HEADER_SIZE); //获取头信息大小
            $header = substr($sContent, 0, $headerSize);
            $body = substr($sContent, $headerSize);
        }
        else
        {
            $header ="";
            $body = $sContent;
        }
        curl_close($curl);
        if($errno==0)
        {
            $parseBody=json_decode($body,true);
            if(isset($parseBody["success"])){
                if($parseBody["success"]){
                    return $this->apiResult(true,"success",0,$parseBody["data"]);
                }else{
                    return $this->apiResult(false,$parseBody["codeMsg"],3);
                }
            }else{
                return $this->apiResult(false,"内容解析失败","1",$body);
            }
        }
        else
        {
            return $this->apiResult(false,$error_msg,2,$body);
        }
    }

    /*
     * api返回信息
     */
    public function apiResult($success=false,$codeMsg="",$code=1,$data=array(),$extra=array())
    {
        $success=(bool) $success;
        $code=intval($code);
        $codeMsg=(string) $codeMsg;
        $map=array(
            "success"=>$success,
            "code"=>$code,
            "codeMsg"=>$codeMsg,
            "data"=>$data,
            "extra"=>$extra,
        );
        return $map;
    }

}


