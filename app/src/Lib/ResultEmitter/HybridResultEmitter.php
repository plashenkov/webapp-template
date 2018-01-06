<?php

namespace App\Lib\ResultEmitter;

use App\Lib\ResultEmitter;

class HybridResultEmitter implements ResultEmitter
{
    /** @var bool */
    protected $jsonPrettyPrint;

    public function __construct($jsonPrettyPrint = false)
    {
        $this->jsonPrettyPrint = $jsonPrettyPrint;
    }

    public function emit($result)
    {
        if (is_array($result) || is_object($result)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(
                $result,
                $this->jsonPrettyPrint ? (JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : 0
            );
        } elseif (isset($result)) {
            header('Content-Type: text/html; charset=utf-8');
            echo $result;
        }
    }
}
