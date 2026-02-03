<?php

declare(strict_types=1);

use MartinCamen\FileSize\Services\FileSizeService;
use MartinCamen\PhpFileSize\FileSize as PhpFileSize;

it('throws BadMethodCallException when calling non-existent method via __callStatic', function (): void {
    FileSizeService::nonExistentMethod(123);
})->throws(BadMethodCallException::class, 'Method nonExistentMethod does not exist on FileSize');

it('throws BadMethodCallException when calling non-existent method via __call', function (): void {
    $service = new FileSizeService();
    $service->nonExistentMethod(123);
})->throws(BadMethodCallException::class, 'Method nonExistentMethod does not exist on FileSize');

it('calls methods via __callStatic that exist on FileSize but not on FileSizeService', function (): void {
    $service = new FileSizeService();

    expect($result = $service->byte())
        ->toBeInstanceOf(PhpFileSize::class)
        ->and($result->toBytes())
        ->toBe(1.0);
});

it('calls methods via __call that exist on FileSize but not on FileSizeService', function (): void {
    expect($result = FileSizeService::byte())
        ->toBeInstanceOf(PhpFileSize::class)
        ->and($result->toBytes())
        ->toBe(1.0);
});

it('injects options when calling method without options argument via __callStatic', function (): void {
    expect($result = FileSizeService::megabyte())
        ->toBeInstanceOf(PhpFileSize::class)
        ->and($result->toKilobytes())
        ->toBe(1024.0);
});

it('passes through options when provided via __callStatic', function (): void {
    $service = new FileSizeService();

    expect($result = $service->megabyte(['precision' => 4]))
        ->toBeInstanceOf(PhpFileSize::class)
        ->and($result->getPrecision())
        ->toBe(4);
});

it('handles methods with value arguments via __call', function (): void {
    expect($result = FileSizeService::kilobytes(500))
        ->toBeInstanceOf(PhpFileSize::class)
        ->and($result->toKilobytes())
        ->toBe(500.0);
});

it('handles methods with value and options arguments via __call', function (): void {
    expect($result = FileSizeService::kilobytes(500, ['precision' => 3]))
        ->toBeInstanceOf(PhpFileSize::class)
        ->and($result->getPrecision())
        ->toBe(3);
});
