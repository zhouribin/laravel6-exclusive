<?php
/**
 * 错误码配置
 */

namespace App\Constants;


class ErrorMsgConstants
{
    const RETURN_SUCCESS = 10000;//操作成功
    const INVALID_REQUEST = 10001;//无效请求
    const VALIDATION_DATA_ERROR = 10002;//请求数据有误
    const RETURN_ERROR = 10003;//操作失败
    const DATA_NOT_EXISTS = 1004;//数据不存在
    const SIGN_ERROR = 10005;//签名错误
    const NOT_HAVE_PERMISSION = 10006;//没有权限
    const DEFAULT_ERROR = 10009;//系统错误
    const TOKEN_ERROR = 10401;//token失效

    public static $errorMsg = [
        self::RETURN_SUCCESS         => '操作成功',
        self::INVALID_REQUEST        => '无效请求',
        self::VALIDATION_DATA_ERROR  => "请求数据有误",
        self::RETURN_ERROR           => '操作失败',
        self::DATA_NOT_EXISTS        => '数据不存在',
        self::SIGN_ERROR             => '签名错误',
        self::NOT_HAVE_PERMISSION    => "没有权限",
        self::DEFAULT_ERROR          => '系统错误',
    ];


}