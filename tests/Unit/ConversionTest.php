<?php

use MartinCamen\FileSize\Facades\FileSize;
use MartinCamen\FileSize\Enums\ByteBase;

it('converts megabytes to kilobytes', function () {
    expect(FileSize::megabytes(2)->kilobytes)
        ->toBe(2048.0);
});

it('converts kilobytes to gigabytes with precision', function () {
    expect(FileSize::kilobytes(2048)->precision(6)->gigabytes)
        ->toBe(0.002048);
});

it('handles singular forms', function () {
    expect(FileSize::megabyte()->kilobytes)
        ->toBe(1024.0);
});

it('chains arithmetic operations', function () {
    $result = FileSize::megabytes(2)
        ->subKilobytes(22)
        ->addKilobytes(8)
        ->kilobytes;

    expect($result)->toBe(2034.0);
});

it('supports decimal byte base', function () {
    $result = FileSize::megabytes(2, ByteBase::Decimal)
        ->kilobytes;

    expect($result)->toBe(2000.0);
});

it('formats for humans', function () {
    expect(FileSize::megabytes(1.5)->forHumans())
        ->toBe('1.50 MiB');
});
