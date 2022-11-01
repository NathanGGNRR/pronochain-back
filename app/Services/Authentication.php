<?php

namespace App\Services;

use App\Contracts\Token;
use App\Models\User;

class Authentication
{
    private Token $token;

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    public function login(array $credentials): string
    {
        return $this->token->generate($credentials);
    }

    public function register(array $credentials)
    {
        $user = null;

        if ($this->token->signatureIsValid($credentials)) {
            $user = User::create([
                'username' => $credentials['username'],
                'eth_address' => $credentials['address'],
            ]);
        }

        return $user;
    }

    public function getRefreshToken(): string
    {
        return $this->token->getRefreshToken();
    }
}
