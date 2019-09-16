<?php

namespace App\Exceptions;

use App\Constants\ErrorMsgConstants;


class ServiceException extends CustomException
{
    public function __construct($statusCode = ErrorMsgConstants::DEFAULT_ERROR, $message = '')
    {
        $message = $message ? $message : (isset(ErrorMsgConstants::$errorMsg[$statusCode]) ? ErrorMsgConstants::$errorMsg[$statusCode] : '');
        parent::__construct($statusCode, $message);
    }
}