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
