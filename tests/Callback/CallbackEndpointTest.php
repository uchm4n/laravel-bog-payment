<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use RedberryProducts\LaravelBogPayment\Events\TransactionStatusUpdated;

function generateRSAKeyPair(): array
{
    $config = [
        'private_key_bits' => 2048,
        'default_md' => 'sha256',
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ];

    $resource = openssl_pkey_new($config);
    openssl_pkey_export($resource, $privateKey);
    $publicKeyDetails = openssl_pkey_get_details($resource);

    return [
        'private_key' => $privateKey,
        'public_key' => $publicKeyDetails['key'],
    ];
}

function generateValidSignature(string $data, string $privateKey)
{
    openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);

    return base64_encode($signature);
}

beforeEach(function () {
    $keys = generateRSAKeyPair();

    $this->privateKey = $keys['private_key'];
    $this->publicKey = $keys['public_key'];

    Config::set('bog-payment.public_key', $keys['public_key']);
});

test('callback with valid signature and payload should trigger event', function () {
    Event::fake();
    $payload = ['body' => ['transaction_id' => '12345', 'status' => 'success']];
    $payloadJson = json_encode($payload);
    $signature = generateValidSignature($payloadJson, $this->privateKey);

    $response = $this->postJson(route('bog-payment.callback'),
        ['body' => ['transaction_id' => '12345', 'status' => 'success']], [
            'Content-Type' => 'application/json',
            'Callback-Signature' => $signature,
        ]);

    $response->assertOk();
    Event::assertDispatched(TransactionStatusUpdated::class);
});

test('callback with invalid signature should return 401', function () {
    $response = $this->postJson(route('bog-payment.callback'),
        ['body' => ['transaction_id' => '12345', 'status' => 'success']], [
            'Content-Type' => 'application/json',
            'Callback-Signature' => 'invalid_signature',
        ]);

    $response->assertStatus(401);
});

test('callback with missing signature should return 401', function () {
    $response = $this->postJson(route('bog-payment.callback'),
        ['body' => ['transaction_id' => '12345', 'status' => 'success']], [
            'Content-Type' => 'application/json',
        ]);

    $response->assertStatus(401);
});

test('callback with invalid JSON should return 401', function () {
    $payload = ['body' => ['transaction_id' => '12345', 'status' => 'success']];
    $response = $this->postJson(route('bog-payment.callback'), $payload, [
        'Content-Type' => 'application/json',
        'Callback-Signature' => generateValidSignature('invalid_json', $this->privateKey),
    ]);

    $response->assertStatus(401);
});

test('callback with missing required fields should return error', function () {
    $payload = json_encode(['unexpected_field' => 'value']);
    $signature = generateValidSignature($payload, $this->privateKey);

    $response = $this->postJson(route('bog-payment.callback'), [], [
        'Content-Type' => 'application/json',
        'Callback-Signature' => $signature,
    ]);

    $response->assertStatus(422);
});

test('callback with missing public key should return 500', function () {
    Config::set('bog-payment.public_key', null);

    $payload = ['body' => ['transaction_id' => '12345', 'status' => 'success']];
    $payloadJson = json_encode($payload);
    $signature = generateValidSignature($payloadJson, $this->privateKey);

    $response = $this->postJson(route('bog-payment.callback'), $payload, [
        'Content-Type' => 'application/json',
        'Callback-Signature' => $signature,
    ]);

    $response->assertStatus(500);
});

test('callback when signature verification fails should return 401', function () {
    $payload = ['body' => ['transaction_id' => '12345', 'status' => 'success']];

    $response = $this->postJson(route('bog-payment.callback'), $payload, [
        'Content-Type' => 'application/json',
        'Callback-Signature' => 'wrong_signature',
    ]);

    $response->assertStatus(401);
});

test('callback when unexpected error occurs should return 500', function () {
    Config::set('bog-payment.public_key', 'invalid_key');

    $payload = json_encode(['body' => ['transaction_id' => '12345', 'status' => 'success']]);
    $signature = generateValidSignature($payload, $this->privateKey);

    $response = $this->postJson(route('bog-payment.callback'),
        ['body' => ['transaction_id' => '12345', 'status' => 'success']], [
            'Content-Type' => 'application/json',
            'Callback-Signature' => $signature,
        ]);

    $response->assertStatus(500);
});

test('callback with valid data should return correct response', function () {
    $payload = ['body' => ['transaction_id' => '12345', 'status' => 'success']];
    $payloadJson = json_encode($payload);
    $signature = generateValidSignature($payloadJson, $this->privateKey);

    $response = $this->postJson(route('bog-payment.callback'), $payload, [
        'Content-Type' => 'application/json',
        'Callback-Signature' => $signature,
    ]);

    $response->assertOk()
        ->assertJson(['transaction_id' => '12345', 'status' => 'success']);
});
