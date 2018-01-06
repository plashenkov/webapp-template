<?php

namespace App\Lib\Validation\Rules;

use App\Lib\Validation\Exception\ParamValidationException;
use App\Lib\Validation\Rule;

class EmailRule extends Rule
{
    /** @var bool */
    private $required;

    public function __construct($required = true, $errorMessage = 'Please specify valid email')
    {
        parent::__construct($errorMessage);
        $this->required = $required;
    }

    public function validate($email, $validator)
    {
        if (!$this->required && empty($email)) return;

        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
            throw new ParamValidationException($this->errorMessage);
        }
    }
}
