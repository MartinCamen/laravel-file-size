<?php

declare(strict_types=1);

use MartinCamen\FileSize\Facades\FileSize;
use MartinCamen\PhpFileSize\Enums\ByteBase;

it('loads configuration from config file', function (): void {
    $fileSize = FileSize::megabytes(10);

    expect($fileSize->options->precision)->toBe(2);
    expect($fileSize->options->decimalSeparator)->toBe('.');
    expect($fileSize->options->thousandsSeparator)->toBe(',');
    expect($fileSize->options->spaceBetweenValueAndUnit)->toBe(true);
    expect($fileSize->options->validationThrowOnNegativeResult)->toBe(false);
    expect($fileSize->options->validationAllowNegativeInput)->toBe(false);
});

it('resolves byte base from configuration', function (): void {
    $fileSize = FileSize::megabytes(10);

    expect($fileSize->options->byteBase())->toBeInstanceOf(ByteBase::class);
});

it('can override configuration values', function (): void {
    config(['file-size.precision' => 4]);

    $fileSize = FileSize::megabytes(10);

    expect($fileSize->options->precision)->toBe(4);
});
