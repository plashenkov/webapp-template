<?php

namespace App\Lib;

class HttpException extends \Exception
{
    protected $statusCode;

    public function __construct($message = 'Internal server error', $statusCode = 500)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public static function notFound($message = 'Not found')
    {
        return new static($message, 404);
    }

    public static function forbidden($message = 'Forbidden')
    {
        return new static($message, 403);
    }

    public static function badRequest($message = 'Bad request')
    {
        return new static($message, 400);
    }

    public static function methodNotAllowed($message = 'Method not allowed')
    {
        return new static($message, 405);
    }

    public static function internalServerError($message = 'Internal server error')
    {
        return new static($message, 500);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
