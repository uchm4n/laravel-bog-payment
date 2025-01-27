<?php

use RedberryProducts\LaravelBogPayment\ApiClient;
use RedberryProducts\LaravelBogPayment\Pay;

beforeEach(function () {
    $this->apiClient = Mockery::mock(ApiClient::class);
    $this->pay = new Pay($this->apiClient);
});

it('Charges payment method per given amount', function () {
    $this->pay->orderId('12345')->amount(100.00);

    $mockResponse = [
        'id' => 'test-id',
        '_links' => [
            'redirect' => ['href' => 'https://example.com/redirect'],
            'details' => ['href' => 'https://example.com/details'],
        ],
    ];

    $this->apiClient->shouldReceive('post')
        ->with("/ecommerce/orders/{$mockResponse['id']}", $this->pay->getPayload())
        ->andReturn($mockResponse);

    $response = $this->pay->chargeCard($mockResponse['id']);

    expect($response)
        ->toHaveKey('id', 'test-id')
        ->toHaveKey('details_url', 'https://example.com/details');
});

it('throws error when no parameters are passed', function () {
    $this->expectException(ArgumentCountError::class);

    $this->pay->chargeCard();
});

it('throws error when null parameter is passed', function () {
    $this->expectException(Exception::class);

    $this->pay->chargeCard(null);
});
