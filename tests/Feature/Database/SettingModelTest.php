<?php

use App\Models\Setting;

it('can get a setting by key', function () {
    Setting::create(['key' => 'test_key', 'value' => 'test_value']);

    expect(Setting::get('test_key'))->toBe('test_value');
});

it('returns default when key not found', function () {
    expect(Setting::get('nonexistent_key', 'default'))->toBe('default');
});

it('can set a setting value', function () {
    Setting::set('another_key', 'another_value');

    $this->assertDatabaseHas('settings', ['key' => 'another_key', 'value' => 'another_value']);
});
