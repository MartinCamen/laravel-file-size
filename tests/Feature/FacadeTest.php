<?php

declare(strict_types=1);

use MartinCamen\FileSize\Facades\FileSize;
use MartinCamen\FileSize\FileSize as FileSizeClass;

it('can be used via the Facade', function (): void {
    $size = FileSize::megabytes(5);

    expect($size)->toBeInstanceOf(FileSizeClass::class);
    expect($size->toMegabytes())->toBe(5.0);
});

it('facade resolves from container correctly', function (): void {
    $instance = app('file-size');

    expect($instance)->toBeInstanceOf(FileSizeClass::class);
});

it('service provider is registered correctly', function (): void {
    expect(app()->getProviders(MartinCamen\FileSize\FileSizeServiceProvider::class))
        ->not->toBeEmpty();
});

it('config file is published', function (): void {
    expect(config('file-size'))->toBeArray();
    expect(config('file-size.precision'))->toBe(2);
});

it('can chain methods via Facade', function (): void {
    $result = FileSize::megabytes(2)
        ->addMegabytes(3)
        ->toMegabytes();

    expect($result)->toBe(5.0);
});
