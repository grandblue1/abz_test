<?php

namespace App\Exceptions;

use Exception;

class ValidationErrorException extends Exception
{
    protected $errors;

    public function __construct($errors, string $message = "", int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }
    public function getErrors()
    {
        return $this->errors;
    }

    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
            'fails' => $this->getErrors(),
        ],$this->getCode());
    }
}
