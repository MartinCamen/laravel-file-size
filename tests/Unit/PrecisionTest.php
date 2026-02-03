<?php

declare(strict_types=1);

use MartinCamen\FileSize\Facades\FileSize;
use MartinCamen\PhpFileSize\Enums\Unit;

describe('precision handling', function (): void {
    it('uses default precision from config', function (): void {
        config(['file-size.precision' => 2]);

        $size = FileSize::kilobytes(1536);

        expect($size->toMegabytes())->toBe(1.5);
    });

    it('can set precision fluently', function (): void {
        $size = FileSize::kilobytes(1536)->precision(4);

        expect($size->getPrecision())->toBe(4);
    });

    it('preserves original when setting precision', function (): void {
        $original = FileSize::megabytes(1)->precision(2);
        $withPrecision = $original->precision(4);

        expect($original->getPrecision())
            ->toBe(2)
            ->and($withPrecision->getPrecision())
            ->toBe(4);
    });

    it('applies precision to conversion methods', function (): void {
        $size = FileSize::bytes(1234567);

        expect($size->toKilobytes(0))
            ->toBe(1206.0)
            ->and($size->toKilobytes(2))
            ->toBe(1205.63)
            ->and($size->toKilobytes(4))
            ->toBe(1205.6318);
    });

    it('applies fluent precision to conversion', function (): void {
        $size = FileSize::bytes(1234567)->precision(0);

        expect($size->toKilobytes())->toBe(1206.0);
    });

    it('method precision overrides fluent precision', function (): void {
        $size = FileSize::bytes(1234567)->precision(0);

        expect($size->toKilobytes(4))->toBe(1205.6318);
    });

    it('uses precision in formatting', function (): void {
        $size = FileSize::megabytes(1.23456);

        expect($size->precision(0)->forHumans())
            ->toBe('1 Megabytes')
            ->and($size->precision(1)->forHumans())
            ->toBe('1.2 Megabytes')
            ->and($size->precision(3)->forHumans())
            ->toBe('1.235 Megabytes');
    });

    it('uses precision in comparisons', function (): void {
        $size = FileSize::kilobytes(1); // 1024 bytes

        // Exact match
        expect($size->precision(0)->equals(1024, Unit::Byte))->toBeTrue();

        // With precision, values are rounded before comparison
        $sizeWithDecimals = FileSize::bytes(1536); // 1.5 KB
        expect($sizeWithDecimals->precision(2)->equals(1.5, Unit::KiloByte))->toBeTrue();
    });
});

describe('magic property access with precision', function (): void {
    it('returns value via magic __get', function (): void {
        $size = FileSize::megabytes(2);

        expect($size->kilobytes)
            ->toBe(2048.0)
            ->and($size->bytes)
            ->toBe(2097152.0);
    });

    it('applies fluent precision to magic __get', function (): void {
        $size = FileSize::bytes(1234567)->precision(0);

        expect($size->kilobytes)->toBe(1206.0);
    });
});
