<?php

namespace app\common;

class Error_code
{
    const  VERIFY_DATA_FAIL ="100"; //数据验证失败
    const  VERIFY_DATA_EMPTY ="101"; //数据验证为空
    const  VERIFY_RULES_EMPTY ="102"; //验证规则为空
    const  VERIFY_TOKEN_EMPTY ="103"; //token为空

    const  UNDEFINED_METHOD ="200"; //未定义方法
    const  OP_FAIL ="200"; //操作失败

    const  SQL_ERROR ="300"; //sql错误
    const  SIGN_ERROR ="400"; //签名错误

    const  CURL_ERROR ="500"; //curl 请求失败
}


