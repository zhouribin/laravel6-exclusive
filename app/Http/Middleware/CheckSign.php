<?php

namespace App\Http\Middleware;


use App\Constants\ErrorMsgConstants;
use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSign
{
    private $logger;

    protected $result = [
        "status_code" => ErrorMsgConstants::SIGN_ERROR,
        "message"     => "签名错误",
        "data"        => [],
    ];

    public function __construct()
    {
        $this->logger = customerLoggerHandle('checkSign');
    }

    public function handle(Request $request, Closure $next)
    {
        if (!$request->has('sign') || !$this->checkVerifySign($request->all())) {
            return $this->returnResponse();
        }

        return $next($request);
    }

    /**
     * @param array $params
     * @return bool
     */
    private function checkVerifySign(array $params)
    {
        $sign = $params['sign'];
        $verifySign = md5(customBuildQuery($params) . env('API_SIGN'));
        loggerWrite($this->logger, 'checkVerifySign', [$sign, $verifySign]);
        return $sign == $verifySign;
    }

    /**
     * @return ResponseFactory|Response
     */
    private function returnResponse()
    {
        return response($this->result, 200);
    }
}
