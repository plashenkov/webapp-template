<?php

namespace App\Lib\Validation\Rules;

use App\Lib\Validation\Exception\ParamValidationException;
use App\Lib\Validation\Rule;

class NotEmptyRule extends Rule
{
    public function __construct($errorMessage = 'Parameter cannot be empty')
    {
        parent::__construct($errorMessage);
    }

    public function validate($value, $validator)
    {
        if (empty($value)) {
            throw new ParamValidationException($this->errorMessage);
        }
    }
}
