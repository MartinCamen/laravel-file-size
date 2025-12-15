<?php

use MartinCamen\FileSize\Enums\ByteBase;
use MartinCamen\FileSize\Facades\FileSize;

it('converts megabytes to kilobytes with binary base as default', function (): void {
    expect(FileSize::megabytes(2)->toKilobytes())
        ->toBe(2048.0);
});

it('converts megabytes to kilobytes with explicit binary base', function (): void {
    expect(FileSize::megabytes(2, ByteBase::Binary)->toKilobytes())
        ->toBe(2048.0);
});

it('converts megabytes to kilobytes with decimal base', function (): void {
    expect(FileSize::megabytes(2, ByteBase::Decimal)->toKilobytes())
        ->toBe(2000.0);
});

it('converts kilobytes to gigabytes with precision', function (): void {
    expect(FileSize::kilobytes(2048, ByteBase::Decimal)->precision(6)->toGigabytes())
        ->toBe(0.002048);
});

it('handles singular forms with binary base', function (): void {
    expect(FileSize::megabyte(ByteBase::Binary)->toKilobytes())
        ->toBe(1024.0);
});

it('handles singular forms with decimal base', function (): void {
    expect(FileSize::megabyte(ByteBase::Decimal)->toKilobytes())
        ->toBe(1000.0);
});

it('chains arithmetic operations with binary base', function (): void {
    $result = FileSize::megabytes(2, ByteBase::Binary)
        ->subKilobytes(22)
        ->addKilobytes(8)
        ->toKilobytes();

    expect($result)->toBe(2034.0);
});

it('chains arithmetic operations with decimal base', function (): void {
    $result = FileSize::megabytes(2, ByteBase::Decimal)
        ->subKilobytes(22)
        ->addKilobytes(8)
        ->toKilobytes();

    expect($result)->toBe(1986.0);
});

it('formats for humans with binary base as default', function (): void {
    expect(FileSize::megabytes(1.5, ByteBase::Binary)->forHumans())
        ->toBe('1.50 Mebibytes');
});

it('formats for humans with binary base', function (): void {
    expect(FileSize::megabytes(1.5, ByteBase::Binary)->forHumans())
        ->toBe('1.50 Mebibytes');
});

it('formats for humans with decimal base', function (): void {
    expect(FileSize::megabytes(1.5, ByteBase::Decimal)->forHumans())
        ->toBe('1.50 Megabytes');
});
