<?php

use RedberryProducts\LaravelBogPayment\ApiClient;
use RedberryProducts\LaravelBogPayment\Pay;

beforeEach(function () {
    $this->apiClient = Mockery::mock(ApiClient::class);
    $this->pay = new Pay($this->apiClient);
});

it('registers new subscription for automatic payment', function () {
    $this->pay->orderId('12345')->amount(100.00);

    $mockResponse = [
        'id' => 'test-id',
        '_links' => [
            'redirect' => ['href' => 'https://example.com/redirect'],
            'details' => ['href' => 'https://example.com/details'],
        ],
    ];

    $this->apiClient->shouldReceive('put')
        ->with("/orders/{$mockResponse['id']}/subscriptions");

    $this->apiClient->shouldReceive('post')
        ->with('/ecommerce/orders', $this->pay->getPayload())
        ->andReturn($mockResponse);

    $response = $this->pay->subscribe();

    expect($response)
        ->toHaveKey('id', 'test-id')
        ->toHaveKey('redirect_url', 'https://example.com/redirect')
        ->toHaveKey('details_url', 'https://example.com/details');
});
