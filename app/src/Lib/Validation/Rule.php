<?php

namespace App\Lib\Validation;

use App\Lib\Validation\Exception\ParamValidationException;

abstract class Rule
{
    /** @var string */
    protected $errorMessage;

    /**
     * Rule constructor.
     * @param string $errorMessage
     */
    public function __construct($errorMessage = 'Validation error')
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @param mixed $value
     * @param Validator $validator
     * @throws ParamValidationException
     */
    abstract public function validate($value, $validator);
}
