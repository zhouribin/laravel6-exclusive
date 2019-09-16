<?php

use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Ramsey\Uuid\Uuid;


/**
 * 解析异常信息
 * @param Exception $e
 * @return array
 */
function getExceptionInfo(Exception $e)
{
    return [
        "Code"    => $e->getCode(),
        "Message" => $e->getMessage(),
        "File"    => $e->getFile(),
        "Line"    => $e->getLine(),
    ];
}


/**
 * 记录日志
 * @param $logName
 * @return Logger
 */
function customerLoggerHandle($logName)
{
    $logName = $logName . "-" . exec('whoami');
    $log = new Logger($logName);
    $logFilePath = storage_path('logs') . "/" . $logName . ".log";
    $log->pushHandler(new RotatingFileHandler($logFilePath, 0, Logger::DEBUG));

    return $log;
}

/**
 * 通用签名方法
 * @param array $data 签名数据
 * @param string $signKey 签名KEY
 * @return mixed
 */
function signServiceRequestData(array $data, string $signKey)
{
    unset($data['sign']);
    ksort($data);
    $sign = md5(customBuildQuery($data) . $signKey);

    return $sign;
}

/**
 * 签名字符串拼接
 * @param array $data
 * @return string
 */
function customBuildQuery(array $data)
{
    unset($data['sign']);
    ksort($data);
    $list = [];
    foreach ($data as $key => $value) {
        if (!is_null($value)) {
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            } elseif (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            $list[] = $key . "=" . $value;
        }
    }

    customerLoggerHandle("sign")->debug("sign", [join("&", $list)]);

    return join("&", $list);
}


/**
 * 分(单位:分)转金额(单位元)
 * @param $price
 * @param int $decimals 小数位数
 * @param bool $isRound 是否四舍五入
 * @return string
 */
function transformPriceToYuan($price, int $decimals = 2, bool $isRound = false)
{
    if ($isRound) {
        $price = round($price / 100, $decimals);
    } else {
        $price = $price / 100;
    }
    return number_format($price, $decimals, ".", "");
}

/**
 * 价格元转换成分
 * @param $price
 * @return int
 */
function transformPriceToCent($price)
{
    return (int)number_format($price * 100, 0, ".", "");
}

/**
 * 获取一个uuid
 * @return string
 * @throws Exception
 */
function generateNewUuid()
{
    return Uuid::uuid4()->toString();
}

/**
 * @param LengthAwarePaginator $models
 * @return array
 */
function getPageInfo(LengthAwarePaginator $models)
{
    return [
        'currentPage' => $models->currentPage(),
        'perPage'     => $models->perPage(),
        'total'       => $models->total(),
    ];
}
