<?php

namespace App\Lib\ErrorHandler;

use App\Lib\HttpException;
use App\Lib\Validation\Exception\ParamValidationException;
use App\Lib\Validation\Exception\ValidationException;
use Whoops\Exception\Frame;
use Whoops\Handler\Handler;

class ErrorHandler extends Handler
{
    /** @var bool */
    protected $isDebug;

    /**
     * ErrorHandler constructor.
     * @param bool $isDebug
     */
    public function __construct($isDebug = false)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * Exception handling.
     * @return int
     */
    public function handle()
    {
        $this->getRun()->sendHttpCode($this->getStatusCode());

        echo json_encode(
            $this->buildJsonResponse(),
            $this->isDebug ? (JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : 0
        );

        return Handler::QUIT;
    }

    /**
     * Builds a response in JSON format.
     * @return array
     */
    protected function buildJsonResponse()
    {
        $exception = $this->getException();

        $response = [
            'error' => [
                'status' => $this->getStatusCode(),
                'message' => $exception->getMessage()
            ]
        ];

        if ($exception instanceof ValidationException) {
            $response['error']['validation_errors'] = $exception->getValidationErrors();
        }

        if ($this->isDebug) {
            $frames = $this->getInspector()->getFrames();
            $trace = [];

            foreach ($frames as $frame) {
                /** @var Frame $frame */
                $trace[] = [
                    'file' => $frame->getFile(),
                    'line' => $frame->getLine(),
                    'class' => $frame->getClass(),
                    'function' => $frame->getFunction(),
                    'args' => $frame->getArgs()
                ];
            }

            $response['error']['debug_info'] = [
                'type' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $trace
            ];
        }

        return $response;
    }

    /**
     * Finds out and returns HTTP error code.
     * @return int
     */
    protected function getStatusCode()
    {
        $exception = $this->getException();

        if ($exception instanceof HttpException) {
            return $exception->getStatusCode();
        }

        if (
            $exception instanceof ValidationException ||
            $exception instanceof ParamValidationException
        ) {
            return 400;
        }

        return 500;
    }

    /**
     * Define content-type.
     * @return string
     */
    public function contentType()
    {
        return 'application/json; charset=utf-8';
    }
}
