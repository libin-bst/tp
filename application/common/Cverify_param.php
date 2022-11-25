<?php

namespace app\common;

use \think\Validate;

class Cverify_param
{
    /**
     * 验证请求
     * @param array $parameter
     * @return array
     */
    public static  function validatorRequest($paramObj="")
    {
        $dataObj=paramsObj();
        $dataObj->data=paramsObj();

        $final_arr=logInit();
        if($final_arr['error_no']==0){
            if(!$paramObj){
                $final_arr=recordLogMsg(Error_level::E_WARNING,Error_code::VERIFY_DATA_EMPTY,"验证数据不能为空");
            }
        }
        if($final_arr['error_no']==0)
        {
            $requestData=request()->param();
            $mixed=Cparam_parse::parseToArr($paramObj,"mixed",0);
            $params=Cparam_parse::parseToArr($paramObj,"params",array());
            $dataObj->data->rules=Cparam_parse::parseToArr($paramObj,"rules",array());
            $dataObj->data->messages=Cparam_parse::parseToArr($paramObj,"messages",array());
            $dataObj->data->dataDefault=Cparam_parse::parseToArr($paramObj,"dataDefault",array());
            if($mixed){
                $dataObj->data->data=array_merge($requestData,$params);
            }else{
                $dataObj->data->data=$params;
            }
            if($final_arr['error_no']==0)
            {
                if(!$dataObj->data->rules){
                    $final_arr=recordLogMsg(Error_level::E_WARNING,Error_code::VERIFY_RULES_EMPTY,"验证规则为空");
                }
            }
            if($final_arr['error_no']==0)
            {
                if($dataObj->data->rules){
                    $validate = new Validate($dataObj->data->rules,$dataObj->data->messages);
                    if (!$validate->check($dataObj->data->data)) {
                        $final_arr=recordLogMsg(Error_level::E_WARNING,Error_code::VERIFY_DATA_FAIL,$validate->getError());
                    }
                }
            }
        }
        if($final_arr['error_no']==0)
        {
            $obj=paramsObj();
            if($dataObj->data->rules){
                foreach ($dataObj->data->rules as $key=>$val){
                    $obj->$key=self::getVal($key,$dataObj->data->data,$dataObj->data->dataDefault);
                }
            }
            $final_arr["result"]=$obj;
        }
        return $final_arr;
    }

    /**
     * 返回数据
     * @param $key
     * @param $data
     * @param $dataDefault
     * @return null
     */
    private static function getVal($key,$data,$dataDefault){
        if(isset($data[$key])){
            $val=$data[$key];
        }else{
            $val=null;
            if(isset($dataDefault[$key])){
                $val=$dataDefault[$key];
            }
        }
        return $val;
    }
}


