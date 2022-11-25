<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


if (! function_exists('impulseSender'))
{
    /**
     * 发号器
     * @return string
     */
    function impulseSender()
    {
        $md_str=time();
        $md_str.=getIPaddress();
        $md_str.=uniqid(mt_rand(), true);
        $md_str.= rand(10000,99999);
        $charid = strtoupper(md5($md_str));
        return $charid;
    }
}

if (! function_exists('getIPaddress'))
{
    /**
     * 获取IP地址
     * @return string
     */
    function getIPaddress()
    {
        if (isset($_SERVER))
        {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            {
                $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
            }
            else if (isset($_SERVER["HTTP_CLIENT_IP"]))
            {
                $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
            }
            else
            {
                $IPaddress = $_SERVER["REMOTE_ADDR"];
            }
        }
        else
        {
            if (getenv("HTTP_X_FORWARDED_FOR"))
            {
                $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
            }
            else if (getenv("HTTP_CLIENT_IP"))
            {
                $IPaddress = getenv("HTTP_CLIENT_IP");
            }
            else
            {
                $IPaddress = getenv("REMOTE_ADDR");
            }
        }
        return $IPaddress;
    }
}

if (! function_exists('paramsObj'))
{
    function paramsObj()
    {
        return new stdClass();
    }
}

if (! function_exists('apiResult'))
{
    /*
     * api返回信息
     */
    function apiResult($success=false,$codeMsg="",$code=1,$data=array(),$extra=array())
    {
        $success=(bool) $success;
        $code=intval($code);
        $codeMsg=(string) $codeMsg;
        if(empty($extra)){
            $extra=paramsObj();
        }
//        if(isset($data["dataType"])){
//            if($data["dataType"]==1){
//
//            }else{
//
//            }
//        }else{
//            if(empty($data)){
//                $data=paramsObj();
//            }
//        }
        $map=array(
            "success"=>$success,
            "code"=>$code,
            "codeMsg"=>$codeMsg,
            "data"=>$data["data"],
            "extra"=>$extra,
        );
        return json_encode($map);
    }
}

if ( ! function_exists('continueExec'))
{
    /**
     * 继续执行
     */
    function continueExec($time=600){
        ignore_user_abort(true);
        set_time_limit($time);
    }
}

