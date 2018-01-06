<?php

namespace App\Lib;

interface ResultEmitter
{
    /**
     * @param mixed $result
     */
    public function emit($result);
}
