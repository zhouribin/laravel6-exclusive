<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ApiMiddleware
{
    private $logger;

    public function __construct()
    {
        $this->logger = customerLoggerHandle('request');
    }

    public function handle(Request $request, Closure $next)
    {
        $msgUniqueKey = "[" . md5(uniqid('api_request_', true)) . "]";
        $this->setUniqueKey($msgUniqueKey);
        loggerWrite(
            $this->logger,
            $request->getPathInfo() . "[Request][" . $request->getMethod() . "]",
            $request->all()
        );

        loggerWrite(
            $this->logger,
            $request->getPathInfo() . "[header][" . $request->getMethod() . "]",
            $request->header()
        );
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '60',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'     => 'Origin, X-Requested-With, Content-Type, Accept, Authorization',
        ];

        if ($request->getMethod() == "OPTIONS") {
            return response()->make('ok', 200, $headers);
        }

        $response = $next($request);

        if ($response instanceof JsonResponse) {
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }
            loggerWrite(
                $this->logger,
                $request->getPathInfo() . "[Response][" . $request->getMethod() . "]",
                [json_decode($response->content(), true)]
            );
        } else {
            loggerWrite(
                $this->logger,
                $request->getPathInfo() . "[Response][" . $request->getMethod() . "]",
                [$response]
            );
        }


        return $response;
    }

    private function setUniqueKey($msgUniqueKey)
    {
        Config::set('PHP_RUN_UNIQUE_KEY', $msgUniqueKey);
    }
}
