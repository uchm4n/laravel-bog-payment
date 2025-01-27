<?php

use RedberryProducts\LaravelBogPayment\ApiClient;
use RedberryProducts\LaravelBogPayment\Pay;

beforeEach(function () {
    $this->apiClient = Mockery::mock(ApiClient::class);
    $this->pay = new Pay($this->apiClient);
});

it('deletes payment method with given payment method id', function () {
    $this->apiClient->shouldReceive('delete')
        ->with('/charges/card/test-id')
        ->once();
    $this->pay->deleteCard('test-id');
});

it('throws error when deleting payment method with null parameter', function () {
    $this->expectException(Exception::class);

    $this->pay->deleteCard(null);
});

it('throws error when deleting payment method without id parameter', function () {
    $this->expectException(ArgumentCountError::class);

    $this->pay->deleteCard();
});
