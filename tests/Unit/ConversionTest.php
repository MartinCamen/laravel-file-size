<?php

use MartinCamen\FileSize\Facades\FileSize;

it('converts megabytes to kilobytes with binary base as default', function (): void {
    expect(FileSize::megabytes(2)->toKilobytes())
        ->toBe(2048.0);
});

it('converts megabytes to kilobytes with explicit binary base', function (): void {
    expect(FileSize::megabytes(2)->inBinaryFormat()->toKilobytes())
        ->toBe(2048.0);
});

it('converts megabytes to kilobytes with decimal base', function (): void {
    expect(FileSize::inDecimalFormat()->megabytes(2)->toKilobytes())
        ->toBe(2000.0);
});

it('converts kilobytes to gigabytes with precision', function (): void {
    expect(FileSize::inDecimalFormat()->kilobytes(2048)->precision(6)->toGigabytes())
        ->toBe(0.002048);
});

it('handles singular forms with binary base', function (): void {
    expect(FileSize::inBinaryFormat()->megabyte()->toKilobytes())
        ->toBe(1024.0);
});

it('handles singular forms with decimal base', function (): void {
    expect(FileSize::inDecimalFormat()->megabyte()->toKilobytes())
        ->toBe(1000.0);
});

it('chains arithmetic operations with binary base', function (): void {
    $result = FileSize::megabytes(2)
        ->inBinaryFormat()
        ->subKilobytes(22)
        ->addKilobytes(8)
        ->toKilobytes();

    expect($result)->toBe(2034.0);
});

it('chains arithmetic operations with decimal base', function (): void {
    $result = FileSize::megabytes(2)
        ->inDecimalFormat()
        ->subKilobytes(22)
        ->addKilobytes(8)
        ->toKilobytes();

    expect($result)->toBe(1986.0);
});

it('chains arithmetic operations with base change', function (): void {
    $result = FileSize::inDecimalFormat()
        ->megabytes(2)
        ->subKilobytes(22)
        ->addKilobytes(8)
        ->inBinaryFormat()
        ->toKilobytes();

    expect($result)->toBe(2034.0);
});

it('formats for humans with binary base as default', function (): void {
    expect(FileSize::megabytes(1.5)->withBinaryLabel()->forHumans())
        ->toBe('1.50 Mebibytes');
});

it('formats for humans with binary base', function (): void {
    expect(FileSize::megabytes(1.5)->withBinaryLabel()->forHumans())
        ->toBe('1.50 Mebibytes');
});

it('formats for humans with decimal label', function (): void {
    expect(FileSize::megabytes(1.5)->withDecimalLabel()->forHumans())
        ->toBe('1.50 Megabytes');
});

it('formats for humans with decimal base', function (): void {
    expect(FileSize::megabytes(1.5)->inDecimalFormat()->forHumans())
        ->toBe('1.50 Megabytes');
});

it('formats for humans with decimal base by default', function (): void {
    expect(FileSize::megabytes(1.5)->forHumans())
        ->toBe('1.50 Megabytes');
});
