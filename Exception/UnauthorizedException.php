<?php

class UnauthorizedException extends Exception
{
    private $HttpCode = 401;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getHttpCode()
    {
        return $this->HttpCode;
    }
}
