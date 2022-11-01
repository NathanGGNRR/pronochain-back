<?php

namespace App\Contracts;

/**
 * Signature Interface.
 *
 * @author Damien Millot <damien.millot@diiage.org>
 */
interface Signature
{
    public function verify(string $signature, string $address, string $message): bool;
}
