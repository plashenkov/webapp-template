<?php

namespace App\Lib\ErrorHandler;

use App\Lib\HttpException;
use App\Lib\Validation\Exception\ParamValidationException;
use App\Lib\Validation\Exception\ValidationException;
use League\Plates\Engine as Plates;
use Whoops\Exception\Frame;
use Whoops\Handler\Handler;
use Whoops\Util\Misc;

/*
 * NOTE: use this class with PrettyPageHandler:
 *   $whoops->pushHandler(new PrettyPageHandler);
 *   $whoops->pushHandler(new HybridErrorHandler($isDebug, $plates));
 */

class HybridErrorHandler extends Handler
{
    /** @var bool */
    protected $isDebug;

    /** @var Plates */
    protected $view;

    /**
     * ErrorHandler constructor.
     * @param bool $isDebug
     * @param Plates $view
     */
    public function __construct($isDebug, Plates $view)
    {
        $this->isDebug = $isDebug;
        $this->view = $view;
    }

    /**
     * Exception handling.
     * @return int
     */
    public function handle()
    {
        $this->getRun()->sendHttpCode($this->getStatusCode());

        if (Misc::isAjaxRequest()) {
            echo json_encode(
                $this->buildJsonResponse(),
                $this->isDebug ? (JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : 0
            );

            return Handler::QUIT;
        }

        if (!$this->isDebug) {
            echo $this->buildHtmlResponse();

            return Handler::QUIT;
        }

        return Handler::DONE;
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
     * Builds a response based on Plates templates.
     * @return string
     */
    protected function buildHtmlResponse()
    {
        if (isset($this->view)) {
            $template = 'errors/' . $this->getStatusCode();

            if (!$this->view->exists($template)) {
                $template = 'errors/all';
            }

            if ($this->view->exists($template)) {
                return $this->view->render($template, [
                    'exception' => $this->getException()
                ]);
            }
        }

        return $this->getException()->getMessage();
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
        return Misc::isAjaxRequest()
            ? 'application/json; charset=utf-8'
            : 'text/html; charset=utf-8';
    }
}
