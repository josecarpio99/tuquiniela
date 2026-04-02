<?php

use Illuminate\Support\Facades\Schema;

it('has wallets table', function () {
    expect(Schema::hasTable('wallets'))->toBeTrue();
});

it('has transactions table', function () {
    expect(Schema::hasTable('transactions'))->toBeTrue();
});

it('has media table', function () {
    expect(Schema::hasTable('media'))->toBeTrue();
});
