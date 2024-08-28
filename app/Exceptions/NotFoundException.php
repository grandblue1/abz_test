<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class NotFoundException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ],$this->getCode() ?: Response::HTTP_NOT_FOUND);
    }
}
