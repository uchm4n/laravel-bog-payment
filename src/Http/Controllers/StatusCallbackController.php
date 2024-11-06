<?php

namespace Jorjika\BogPayment\Http\Controllers;

use HttpException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Nikajorjika\BogPayment\Events\TransactionStatusUpdated;


class StatusCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $requestBody = $request->getContent();
        $signature = $request->header('Callback-Signature');
        $publicKey = config('services.bog.public_key');

        $this->ensureSignatureIsValid($requestBody, $signature, $publicKey);

        event(TransactionStatusUpdated::class, json_decode($requestBody, true)['body']);

        return json_decode($requestBody, true)['body'];
    }

    /**
     * Verifies the signature using the provided public key.
     *
     * @param  string  $data  The data to verify.
     * @param  string  $signature  The provided signature.
     * @param  string  $publicKey  The public key to use for verification.
     *
     * @return bool
     */
    private function verifySignature(string $data, string $signature, string $publicKey): bool
    {
        // Decode the signature from base64
        $decodedSignature = base64_decode($signature);
        $publicKey = openssl_pkey_get_public($publicKey);
        $verified = openssl_verify($data, $decodedSignature, $publicKey, 'RSA-SHA256');

        return $verified === 1;
    }

    /**
     * @throws HttpException
     */
    private function ensureSignatureIsValid($body, $signature, $publicKey)
    {
        if (!$this->verifySignature($body, $signature, $publicKey)) {
            throw new HttpException('Invalid Signature', 401);
        }

    }
}
