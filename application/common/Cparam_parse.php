<?php

namespace app\common;

class Cparam_parse
{
	/**
	 * 把数据转换成数组
	 * @param $param
	 * @return array|mixed|string
	 */
	public static function parseToArr($param,$key="",$default="")
	{
	    if($param)
	    {
            if(is_object($param))
            {
                $result=\objToArray($param);
            }
            else
            {
                $result=$param;
            }
            if($key)
            {
                return isset($result[$key])?$result[$key]:$default;
            }
            else
            {
                return $result;
            }
        }
	    else
        {
            return $default;
        }
	}
}


