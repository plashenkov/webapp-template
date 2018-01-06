<?php

namespace App\Lib\Validation\Rules;

use App\Lib\Validation\Exception\ParamValidationException;
use App\Lib\Validation\Rule;

class IntegerRule extends Rule
{
    /** @var bool */
    private $required;

    public function __construct($required = true, $errorMessage = 'Value must be an integer')
    {
        parent::__construct($errorMessage);
        $this->required = $required;
    }

    public function validate($value, $validator)
    {
        if (!$this->required && empty($value)) return;

        if (!ctype_digit($value)) {
            throw new ParamValidationException($this->errorMessage);
        }
    }
}
