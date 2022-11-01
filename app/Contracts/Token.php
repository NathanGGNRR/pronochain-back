<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Token Interface.
 *
 * @author Damien Millot <damien.millot@diiage.org>
 */
interface Token
{
    public function generate(array $credentials): string;

    public function refresh(string $refresh_token): string;

    public function parse(string $token);

    public function validateBase64(string $token): bool;

    public function validate($token): bool;

    public function getRefreshToken(): string;

    public function getRefreshTokenExpiresIn(): int;

    public function getAuthenticatable(string $token): Model;

    public function signatureIsValid(array $credentials): bool;
}
