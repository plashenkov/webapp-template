<?php

namespace App\Lib\ResultEmitter;

interface ResultEmitter
{
    /**
     * @param mixed $result
     */
    public function emit($result);
}
