<?php

declare(strict_types=1);

use MartinCamen\FileSize\Configuration\FileSizeConfiguration;
use MartinCamen\FileSize\Enums\ByteBase;

it('loads configuration from config file', function (): void {
    $config = app(FileSizeConfiguration::class);

    expect($config->precision)->toBe(2);
    expect($config->decimalSeparator)->toBe('.');
    expect($config->thousandsSeparator)->toBe(',');
    expect($config->spaceBetweenValueAndUnit)->toBe(true);
    expect($config->validationThrowOnNegativeResult)->toBe(false);
    expect($config->validationAllowNegativeInput)->toBe(false);
});

it('resolves byte base from configuration', function (): void {
    $config = app(FileSizeConfiguration::class);

    expect($config->byteBase())->toBeInstanceOf(ByteBase::class);
});

it('can override configuration values', function (): void {
    config(['file-size.precision' => 4]);

    $config = app(FileSizeConfiguration::class);

    expect($config->precision)->toBe(4);
});
