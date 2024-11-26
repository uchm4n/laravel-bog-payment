<?php

use Jorjika\BogPayment\ApiClient;
use Jorjika\BogPayment\Card;

beforeEach(function () {
    $this->apiClient = Mockery::mock(ApiClient::class);
    $this->card = new Card($this->apiClient);
});

it('throws exception if payment method id is not provided', function () {
    $this->card->orderId('12345')->amount(100.00);

    $this->apiClient->shouldReceive('post')
        ->with('/ecommerce/orders', $this->card->getPayload())
        ->andReturn(['id' => 'test-id']);

    $this->expectException(Exception::class);

    $this->card->charge(null);
});

it('charges payment method with given payment method id', function () {
    $this->card->orderId('12345')->amount(100.00);

    $mockResponse = [
        'id' => 'test-id',
        '_links' => [
            'redirect' => ['href' => 'https://example.com/redirect'],
            'details' => ['href' => 'https://example.com/details'],
        ],
    ];

    $this->apiClient->shouldReceive('post')
        ->with('/ecommerce/orders/test-id', $this->card->getPayload())
        ->andReturn($mockResponse);

    $response = $this->card->charge('test-id');

    expect($response)->toHaveKey('id', 'test-id');
});
