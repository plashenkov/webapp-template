<?php

namespace App\Lib\Validation\Exception;

class ValidationException extends \Exception
{
    private $validationErrors;

    /**
     * ValidationException constructor.
     * @param array $validationErrors
     * @inheritdoc
     */
    public function __construct(
        array $validationErrors,
        $message = 'Validation error',
        $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->validationErrors = $validationErrors;
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}
