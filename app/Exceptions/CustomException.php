<?php
namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

Abstract class CustomException extends HttpException
{
    public function __construct($statusCode, $message = null, Exception $previous = null, array $headers = array(), $code = 0)
    {
        parent::__construct(200, $message, $previous, $headers, $statusCode);
    }
}
