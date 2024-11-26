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
