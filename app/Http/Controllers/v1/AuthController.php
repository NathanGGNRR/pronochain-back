<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Authentication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    private Authentication $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authentication->login($request->input('credentials'));

        return response()->json([
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.time_to_live_access'),
            'access_token' => $token,
            'refresh_token' => $this->authentication->getRefreshToken(),
            'refresh_token_expires_in' => config('jwt.time_to_live_refresh'),
        ], Response::HTTP_OK, ['Authorization' => "Bearer {$token}"]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authentication->register($request->input('credentials'));

        return response()->json(new UserResource($user), Response::HTTP_CREATED);
    }

    public function me(): JsonResponse
    {
        return response()->json(new UserResource(auth()->user()));
    }
}
