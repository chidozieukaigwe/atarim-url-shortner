<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class EncodeDecodeFailureException extends Exception
{
    public function __construct($message = "Service Failure", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => $this->getMessage()], 500);
        }

        return redirect()->back()->withInput()->withErrors([$this->getMessage()]);
    }
}
