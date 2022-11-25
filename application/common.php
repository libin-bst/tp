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
        $map=array(
            "success"=>$success,
            "code"=>$code,
            "codeMsg"=>$codeMsg,
            "data"=>$data,
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

if (! function_exists('toJsonDecode'))
{
    function toJsonDecode($data,$default=""){
        if(empty($data)){
            return $default;
        }else{
            return json_decode($data,true);
        }
    }
}

if (! function_exists('objToArray'))
{
    /**
     * 将对象转换成数组
     * @param $obj
     * @return mixed
     */
    function objToArray($obj)
    {
        return json_decode(json_encode($obj), true);
    }
}

if (! function_exists('toJsonEncode'))
{
    function toJsonEncode($data,$default="")
    {
        if(empty($data)){
            return $default;
        }else{
            return json_encode($data);
        }
    }
}

/**
 * 日志初始化格式
 * @return array
 */
function logInit()
{
    $result=array(
        "error_level"=>0, //错误级别
        "error_no"=>0, //返回给请求方的错误号
        "error_msg"=>"",//返回给请求方的错误信息
        "log_msg"=>"",//实际错误信息
        "execute_sql"=>"",//执行的SQL

        "call_line"=>"", //调用行数
        "call_file"=>"", //调用文件
        "call_function"=>"", //调用函数
        "call_class"=>"", //调用类

        "call_final_error_msg"=>"", //错误信息
        "call_final_log_msg"=>"", //实际错误信息
        "call_final_execute_sql"=>"", //执行的SQL
        "call_final_line"=>"",//错误行数
        "call_final_file"=>"",//错误文件
        "call_final_function"=>"",//调用函数
        "call_final_class"=>"",//调用类

        "debug_backtrace"=>"",//代码执行过程
        "result"=>array(),
        "resultType"=>"",
        "extra"=>array(),
    );
    return $result;
}

/**
 * 执行过程记录
 * @return string
 */
function debugList()
{
    $debug=debug_backtrace();

    array_shift($debug);
    array_pop($debug);
    array_pop($debug);

    $final=array();
    if($debug)
    {
        foreach($debug as $key=>$val)
        {
            $final[$key]["file"]=isset($val["file"])?$val["file"]:"";
            $final[$key]["line"]=isset($val["line"])?$val["line"]:"";
            $final[$key]["args"]=isset($val["args"])?$val["args"]:"";
            $final[$key]["function"]=isset($val["function"])?$val["function"]:"";
            $final[$key]["class"]=isset($val["class"])?$val["class"]:"";
        }
    }
    return json_encode($final);
}

/**
 * 获取调用处信息
 * @param int $index
 * @return array
 */
function debugInfo($index=2)
{
    $debug=debug_backtrace();
    $callInfo=isset($debug[$index])?$debug[$index]:array();
    unset($debug);
    $data=array();
    $data['call_file']=isset($callInfo['file'])?$callInfo['file']:"";
    $data['call_line']=isset($callInfo['line'])?$callInfo['line']:"";
    $data['call_function']=isset($callInfo['function'])?$callInfo['function']:"";
    $data['call_class']=isset($callInfo['class'])?$callInfo['class']:"";
    return $data;
}

/**
 * 记录错误信息
 * @param array $final_arr
 * @param int $error_no
 * @param string $error_msg
 * @param string $log_msg
 * @return array
 */
function recordLogMsg($error_level,$error_no,$error_msg,$log_msg="",$extra=array())
{
    $data=logInit();
    $data["error_level"]=$error_level;
    $data["error_no"]=$error_no;
    $data["error_msg"]=$error_msg;
    $data["log_msg"]=$log_msg;
    $data["extra"]=is_string($extra)?$extra:toJsonEncode($extra);
    $data['debug_backtrace']=debugList();

    $debugInfo=debugInfo();
    $data=array_merge($data,$debugInfo);
    return $data;
}

/**
 * 合并调用函数信息到当前记录
 * @param array $final_arr
 * @param $checkResult
 * @return array
 */
function logCallErrorMsg($final_arr=array(),$checkResult)
{
    $data=array();
    $data['error_level']=$checkResult['error_level'];
    $data['error_no']=$checkResult['error_no'];
    $data['error_msg']=$checkResult['error_msg'];
    $data['log_msg']=$checkResult['log_msg'];
    $data['call_final_error_msg']=$checkResult['error_msg'];
    $data['call_final_log_msg']=$checkResult['log_msg'];
    $data['call_final_execute_sql']=$checkResult['execute_sql'];
    $data['call_final_line']=$checkResult['call_line'];
    $data['call_final_file']=$checkResult['call_file'];
    $data['call_final_function']=$checkResult['call_function'];
    $data['call_final_class']=$checkResult['call_class'];
    $data['debug_backtrace']=$checkResult['debug_backtrace'];
    $data['extra']=$checkResult['extra'];
    $data=array_merge($final_arr,$data);
    return $data;
}

