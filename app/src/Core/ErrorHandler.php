<?php

namespace App\Core;

use App\Lib\HttpException;
use League\Plates\Engine as Plates;
use Whoops\Exception\Frame;
use Whoops\Handler\Handler;
use Whoops\Util\Misc;

class ErrorHandler extends Handler
{
    /** @var bool */
    protected $isDebug;

    /** @var Plates */
    protected $view;

    /**
     * ErrorHandler constructor.
     * @param bool $isDebug Определяет, включен ли режим отладки.
     * @param Plates $view
     */
    public function __construct($isDebug, Plates $view)
    {
        $this->isDebug = $isDebug;
        $this->view = $view;
    }

    /**
     * Обработка исключительной ситуации.
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
     * Формирует ответ в формате JSON.
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
     * Формирует ответ на основе view.
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
     * Вычисляет и возвращает код ошибки HTTP.
     * @return int
     */
    protected function getStatusCode()
    {
        $exception = $this->getException();

        if ($exception instanceof HttpException) {
            return $exception->getStatusCode();
        }

        return 500;
    }

    /**
     * Возвращает content-type.
     * @return string
     */
    public function contentType()
    {
        return Misc::isAjaxRequest()
            ? 'application/json; charset=utf-8'
            : 'text/html; charset=utf-8';
    }
}
