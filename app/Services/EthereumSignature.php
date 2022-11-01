<?php

namespace App\Services;

use App\Contracts\Signature;
use Elliptic\Curve\ShortCurve\Point;
use Elliptic\EC;
use Exception;
use Illuminate\Support\Str;
use kornrunner\Keccak;

class EthereumSignature implements Signature
{
    /**
     * @throws Exception
     */
    public function verify(string $signature, string $address, string $message): bool
    {
        $messageLength = Str::length($message);
        $hash = Keccak::hash("\x19Ethereum Signed Message:\n{$messageLength}{$message}", 256);

        $sign = [
            'r' => Str::substr($signature, 2, 64),
            's' => Str::substr($signature, 66, 64),
        ];

        $recId = ord(hex2bin(Str::substr($signature, 130, 2))) - 27;

        if ($recId !== ($recId & 1)) {
            return false;
        }

        // Recover public key using elliptic curve technical
        // Ethereum is using the secp256k1 curve (http://www.secg.org/sec2-v2.pdf)
        $publicKey = (new EC('secp256k1'))->recoverPubKey($hash, $sign, $recId);

        return $this->publicKeyToAddress($publicKey) === Str::lower($address);
    }

    /**
     * @throws Exception
     */
    private function publicKeyToAddress(Point $publicKey): string
    {
        return '0x'.substr(Keccak::hash(substr(hex2bin($publicKey->encode('hex')), 1), 256), 24);
    }
}
