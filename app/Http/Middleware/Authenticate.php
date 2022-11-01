<?php

namespace App\Http\Middleware;

use App\Contracts\Token;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psr\SimpleCache\InvalidArgumentException;

class Authenticate
{
    private Token $token;

    /**
     * Auth constructor.
     */
    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    /**
     * Handle an incoming request.
     *
     * @throws InvalidArgumentException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (empty($request->bearerToken())) {
            return response()->json(
                ['title' => 'Unauthenticated', 'message' => __('auth.invalid_or_missing_token')],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $this->isAuthorized((string) $request->bearerToken());

        if (User::class === $this->token->parse($request->bearerToken())->claims()->get('model')) {
            $this->setAuthUser($request->bearerToken());
        }

        return $next($request);
    }

    /**
     * Set auth user.
     */
    protected function setAuthUser(string $token): void
    {
        auth()->setUser($this->token->getAuthenticatable($token));
    }

    private function isAuthorized(string $token): bool
    {
        if (!$this->token->validateBase64($token)) {
            abort(401, __('auth.invalid_or_missing_token'));
        }

        return true;
    }
}
