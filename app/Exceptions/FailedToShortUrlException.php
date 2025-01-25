<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class FailedToShortUrlException extends Exception
{

    public function __construct($message = "Failed to shorten the given url", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => $this->getMessage()], 422);
        }

        return redirect()->back()->withInput()->withErrors([$this->getMessage()]);
    }
}
