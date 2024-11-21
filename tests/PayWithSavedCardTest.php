<?php

use Jorjika\BogPayment\ApiClient;
use Jorjika\BogPayment\Pay;

beforeEach(function () {
    $this->apiClient = Mockery::mock(ApiClient::class);
    $this->pay = new Pay($this->apiClient);
});

it('registers card if saveCard is true', function () {
    $this->pay->orderId('12345')->amount(100.00)->saveCard();

    $mockResponse = [
        'id' => 'test-id',
        '_links' => [
            'redirect' => ['href' => 'https://example.com/redirect'],
            'details' => ['href' => 'https://example.com/details'],
        ],
    ];

    $this->apiClient->shouldReceive('post')
        ->with('/ecommerce/orders', $this->pay->getPayload())
        ->andReturn($mockResponse);

    $this->apiClient->shouldReceive('put')
        ->with('/orders/test-id/cards')
        ->andReturn(['status' => 'success']);

    $response = $this->pay->process();

    expect($response)->toHaveKey('id', 'test-id');
});

it('does not register card if saveCard is false', function () {
    $this->pay->orderId('12345')->amount(100.00);

    $mockResponse = [
        'id' => 'test-id',
        '_links' => [
            'redirect' => ['href' => 'https://example.com/redirect'],
            'details' => ['href' => 'https://example.com/details'],
        ],
    ];

    $this->apiClient->shouldReceive('post')
        ->with('/ecommerce/orders', $this->pay->getPayload())
        ->andReturn($mockResponse);

    $this->apiClient->shouldNotReceive('put');

    $response = $this->pay->process();

    expect($response)->toHaveKey('id', 'test-id');
});

it('throws exception if payment method id is not provided', function () {
    $this->pay->orderId('12345')->amount(100.00);

    $this->apiClient->shouldReceive('post')
        ->with('/ecommerce/orders', $this->pay->getPayload())
        ->andReturn(['id' => 'test-id']);

    $this->expectException(Exception::class);

    $this->pay->chargePaymentMethod(null);
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

    $this->apiClient->shouldReceive('post')
        ->with('/ecommerce/orders/test-id', $this->pay->getPayload())
        ->andReturn($mockResponse);

    $response = $this->pay->chargePaymentMethod('test-id');

    expect($response)->toHaveKey('id', 'test-id');
});
