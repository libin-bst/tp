<?php

namespace  app\common;

class Sign_md5{

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
     * 检测数据是否过期
     * @param $data
     * @return bool
     */
    public  function checkValidity($data){
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
     * 签名算法MD5
     * @param $data
     * @param $appKey
     * @param string $str
     * @return string
     */
    public  function sign($data,$appKey,&$str="")
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
     * 验证数据：1、是否含有公共字段，2、签名是否正确，3、数据是否过期
     * @param $data
     * @param $appKey
     * @param string $str
     * @return bool
     */
    public  function verifySign($data,$appKey,&$str=""){
        if(self::checkCommonField($data,$appKey)){
            $signature=$data['signature'];
            $sign=self::sign($data,$appKey,$str);
            if(strcasecmp($signature,$sign)==0){
                return self::checkValidity($data);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function __call($name,$arguments)
    {
        return recordLogMsg(Error_level::E_WARNING,Error_code::UNDEFINED_METHOD,"未定义方法",$name,$arguments);
    }

}