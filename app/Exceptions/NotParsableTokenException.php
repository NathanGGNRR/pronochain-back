<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class NotParsableTokenException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'title' => 'Bad token',
            'message' => 'The token can\'t be parsed',
        ], Response::HTTP_UNAUTHORIZED);
    }
}
