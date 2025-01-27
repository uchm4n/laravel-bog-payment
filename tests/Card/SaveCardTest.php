<?php

use RedberryProducts\LaravelBogPayment\ApiClient;
use RedberryProducts\LaravelBogPayment\Pay;

beforeEach(function () {
    $this->apiClient = Mockery::mock(ApiClient::class);
    $this->pay = new Pay($this->apiClient);
});

it('charges payment method with given payment method id', function () {
    $this->pay->orderId('12345')->amount(100.00);

    $mockResponse = [
        'id' => 'test-id',
        '_links' => [
            'redirect' => ['href' => 'https://example.com/redirect'],
            'details' => ['href' => 'https://example.com/details'],
        ],
    ];

    $this->apiClient->shouldReceive('put')
        ->with("/orders/{$mockResponse['id']}/cards");

    $this->apiClient->shouldReceive('post')
        ->with('/ecommerce/orders', $this->pay->getPayload())
        ->andReturn($mockResponse);

    $response = $this->pay->saveCard();

    expect($response)->toHaveKey('id', 'test-id');
});

it('throws exception if payment method id is not provided', function () {
    $this->pay->orderId('12345')->amount(100.00);

    $this->apiClient->shouldReceive('post')
        ->with('/ecommerce/orders', $this->pay->getPayload())
        ->andReturn(['id' => 'test-id']);

    $this->expectException(Exception::class);

    $this->pay->saveCard(null);
});
