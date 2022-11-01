<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class InvalidRefreshTokenException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'title' => 'Bad token',
            'message' => 'Invalid refresh token',
        ], Response::HTTP_UNAUTHORIZED);
    }
}
