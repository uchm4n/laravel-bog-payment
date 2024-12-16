<?php

use Jorjika\BogPayment\ApiClient;
use Jorjika\BogPayment\Pay;

beforeEach(function () {
    $this->apiClient = Mockery::mock(ApiClient::class);
    $this->pay = new Pay($this->apiClient);
});

it('initializes payload in constructor', function () {
    expect($this->pay->getPayload())
        ->toHaveKey('callback_url', config('bog-payment.callback_url'))
        ->toHaveKey('redirect_urls', config('bog-payment.redirect_urls'));
});

it('processes payment and returns response', function () {
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

    $response = $this->pay->process();

    expect($response)
        ->toHaveKey('id', 'test-id')
        ->toHaveKey('redirect_url', 'https://example.com/redirect')
        ->toHaveKey('details_url', 'https://example.com/details');
});

it('can set custom basket array', function () {
    $basket = [
        ['name' => 'Product 1', 'quantity' => 1, 'unit_price' => 100.00],
        ['name' => 'Product 2', 'quantity' => 2, 'unit_price' => 50.00],
    ];

    $this->pay->orderId('12345')->redirectUrl('https://example.com/status')->amount(200.00, 'GEL', $basket);

    expect($this->pay->getPayload())->toMatchArray([
        'callback_url' => config('bog-payment.callback_url'),
        'external_order_id' => '12345',
        'purchase_units' => [
            'currency' => 'GEL',
            'total_amount' => 200.00,
            'basket' => [
                [
                    'name' => 'Product 1',
                    'quantity' => 1,
                    'unit_price' => 100.00,
                ],
                [
                    'name' => 'Product 2',
                    'quantity' => 2,
                    'unit_price' => 50.00,
                ],
            ],
        ],
        'redirect_urls' => [
            'fail' => 'https://example.com/status',
            'success' => 'https://example.com/status',
        ],
    ]);
});
