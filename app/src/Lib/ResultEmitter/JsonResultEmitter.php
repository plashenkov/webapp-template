<?php

namespace App\Lib\ResultEmitter;

class JsonResultEmitter implements ResultEmitter
{
    /** @var bool */
    protected $jsonPrettyPrint;

    public function __construct($jsonPrettyPrint = false)
    {
        $this->jsonPrettyPrint = $jsonPrettyPrint;
    }

    public function emit($result)
    {
        if (isset($result)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(
                $result,
                $this->jsonPrettyPrint ? (JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : 0
            );
        }
    }
}
