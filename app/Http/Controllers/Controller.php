<?php

namespace App\Http\Controllers;

use App\Constants\ErrorMsgConstants;
use App\Exceptions\ServiceException;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $result = [
        "statusCode" => ErrorMsgConstants::RETURN_SUCCESS,
        "message"     => "",
        "data"        => []
    ];

    /**
     * 操作成功返回值
     * @param $responseData
     * @return array
     */
    protected function wrapSuccessReturn(array $responseData)
    {
        foreach ($responseData as $key => $value) {
            $this->result[$key] = $value;
        }

        return $this->result;
    }

    /**
     * 操作失败的返回值
     * @param $exception
     * @return array
     */
    protected function wrapErrorReturn(Exception $exception)
    {
        $logger = customerLoggerHandle("ErrorReturn");
        $logger->debug("接口异常", getExceptionInfo($exception));
        $this->result['statusCode'] = ErrorMsgConstants::DEFAULT_ERROR;
        $this->result['message'] = ErrorMsgConstants::$errorMsg[ErrorMsgConstants::DEFAULT_ERROR];
        $this->result['data'] = [];

        if ($exception instanceof ServiceException) {
            $this->result['message'] = $exception->getMessage();
        } else {
            $this->result['statusCode'] = $exception->getCode();
            $this->result['message'] = $exception->getMessage();
        }

        return $this->result;
    }

    /**
     * 请求参数验证
     * @param Request $request
     * @param array $validatorRules 验证规则
     * @param array $validatorMessages 验证提示
     */
    protected function requestValidator(Request $request, array $validatorRules, array $validatorMessages = [])
    {
        $validator = Validator::make($request->all(), $validatorRules, $validatorMessages);
        if ($validator->fails()) {
            throw new ServiceException(ErrorMsgConstants::VALIDATION_DATA_ERROR, $validator->getMessageBag()->first());
        }
    }
}
