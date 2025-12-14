<?php

declare(strict_types=1);

use MartinCamen\FileSize\FileSize;

/**
 * Note: The FileSize class uses static factory methods with a private constructor.
 * This means you should use the class directly rather than through a Laravel Facade.
 * The Facade is provided for IDE autocompletion and documentation purposes.
 */
it('can be used directly via static factory methods', function (): void {
    $size = FileSize::megabytes(5);

    expect($size)->toBeInstanceOf(FileSize::class);
    expect($size->toMegabytes())->toBe(5.0);
});

it('service provider is registered correctly', function (): void {
    expect(app()->getProviders(MartinCamen\FileSize\FileSizeServiceProvider::class))
        ->not->toBeEmpty();
});

it('config file is published', function (): void {
    expect(config('file-size'))->toBeArray();
    expect(config('file-size.precision'))->toBe(2);
});
