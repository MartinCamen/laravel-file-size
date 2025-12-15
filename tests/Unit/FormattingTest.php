<?php

declare(strict_types=1);

use MartinCamen\FileSize\Enums\ByteBase;
use MartinCamen\FileSize\Facades\FileSize;

describe('forHumans', function (): void {
    it('formats bytes', function (): void {
        $size = FileSize::bytes(512, ByteBase::Binary);

        expect($size->forHumans())->toBe('512.00 Bytes');
    });

    it('formats kilobytes', function (): void {
        $size = FileSize::kilobytes(1.5, ByteBase::Binary);

        expect($size->forHumans())->toBe('1.50 Kibibytes');
    });

    it('formats megabytes', function (): void {
        $size = FileSize::megabytes(1.5, ByteBase::Binary);

        expect($size->forHumans())->toBe('1.50 Mebibytes');
    });

    it('formats gigabytes', function (): void {
        $size = FileSize::gigabytes(2.5, ByteBase::Binary);

        expect($size->forHumans())->toBe('2.50 Gibibytes');
    });

    it('formats terabytes', function (): void {
        $size = FileSize::terabytes(1.25, ByteBase::Binary);

        expect($size->forHumans())->toBe('1.25 Tebibytes');
    });

    it('formats petabytes', function (): void {
        $size = FileSize::petabytes(1.75, ByteBase::Binary);

        expect($size->forHumans())->toBe('1.75 Pebibytes');
    });

    it('uses short labels', function (): void {
        $size = FileSize::megabytes(1.5, ByteBase::Binary);

        expect($size->forHumans(short: true))->toBe('1.50 MiB');
    });

    it('uses custom precision', function (): void {
        $size = FileSize::megabytes(1.5678, ByteBase::Binary);

        expect($size->forHumans(precision: 3))->toBe('1.568 Mebibytes');
    });

    it('uses decimal labels for decimal base', function (): void {
        $size = FileSize::megabytes(1.5, ByteBase::Decimal);

        expect($size->forHumans())->toBe('1.50 Megabytes');
    });

    it('uses short decimal labels', function (): void {
        $size = FileSize::megabytes(1.5, ByteBase::Decimal);

        expect($size->forHumans(short: true))->toBe('1.50 MB');
    });

    it('accepts binary label byte base for binary byte base', function (): void {
        $size = FileSize::megabytes(2.52, ByteBase::Binary);

        expect($size->forHumans(labelStyle: ByteBase::Binary))->toBe('2.52 Mebibytes');
    });

    it('accepts decimal label byte base for binary byte base', function (): void {
        $size = FileSize::megabytes(2.52, ByteBase::Binary);

        expect($size->forHumans(labelStyle: ByteBase::Decimal))->toBe('2.52 Megabytes');
    });

    it('accepts binary label byte base for decimal byte base', function (): void {
        $size = FileSize::megabytes(2.52, ByteBase::Decimal);

        expect($size->forHumans(labelStyle: ByteBase::Binary))->toBe('2.52 Mebibytes');
    });

    it('accepts decimal label byte base for decimal byte base', function (): void {
        $size = FileSize::megabytes(2.52, ByteBase::Decimal);

        expect($size->forHumans(labelStyle: ByteBase::Decimal))->toBe('2.52 Megabytes');
    });
});

describe('format alias', function (): void {
    it('formats with long labels', function (): void {
        $size = FileSize::megabytes(2.5, ByteBase::Binary);

        expect($size->format())->toBe('2.50 Mebibytes');
    });

    it('accepts precision parameter', function (): void {
        $size = FileSize::megabytes(2.567, ByteBase::Binary);

        expect($size->format(precision: 1))->toBe('2.6 Mebibytes');
    });

    it('accepts binary label byte base for binary byte base', function (): void {
        $size = FileSize::megabytes(2.52, ByteBase::Binary);

        expect($size->format(labelStyle: ByteBase::Binary))->toBe('2.52 Mebibytes');
    });

    it('accepts decimal label byte base for binary byte base', function (): void {
        $size = FileSize::megabytes(2.52, ByteBase::Binary);

        expect($size->format(labelStyle: ByteBase::Decimal))->toBe('2.52 Megabytes');
    });

    it('accepts binary label byte base for decimal byte base', function (): void {
        $size = FileSize::megabytes(2.52, ByteBase::Decimal);

        expect($size->format(labelStyle: ByteBase::Binary))->toBe('2.52 Mebibytes');
    });

    it('accepts decimal label byte base for decimal byte base', function (): void {
        $size = FileSize::megabytes(2.52, ByteBase::Decimal);

        expect($size->format(labelStyle: ByteBase::Decimal))->toBe('2.52 Megabytes');
    });
});

describe('formatShort alias', function (): void {
    it('formats with short labels', function (): void {
        $size = FileSize::megabytes(2.5, ByteBase::Binary);

        expect($size->formatShort())->toBe('2.50 MiB');
    });

    it('accepts precision parameter', function (): void {
        $size = FileSize::megabytes(2.567, ByteBase::Binary);

        expect($size->formatShort(precision: 1))->toBe('2.6 MiB');
    });
});

describe('best unit selection', function (): void {
    it('selects bytes for small values', function (): void {
        $size = FileSize::bytes(512, ByteBase::Binary);

        expect($size->forHumans())->toContain('Bytes');
    });

    it('selects kilobytes for KB range', function (): void {
        $size = FileSize::bytes(2048, ByteBase::Binary);

        expect($size->forHumans())->toContain('Kibibytes');
    });

    it('selects megabytes for MB range', function (): void {
        $size = FileSize::kilobytes(2048, ByteBase::Binary);

        expect($size->forHumans())->toContain('Mebibytes');
    });

    it('selects gigabytes for GB range', function (): void {
        $size = FileSize::megabytes(2048, ByteBase::Binary);

        expect($size->forHumans())->toContain('Gibibytes');
    });

    it('selects terabytes for TB range', function (): void {
        $size = FileSize::gigabytes(2048, ByteBase::Binary);

        expect($size->forHumans())->toContain('Tebibytes');
    });

    it('selects petabytes for PB range', function (): void {
        $size = FileSize::terabytes(2048, ByteBase::Binary);

        expect($size->forHumans())->toContain('Pebibytes');
    });
});

describe('configuration options', function (): void {
    it('uses configured decimal separator', function (): void {
        config(['file-size.formatting.decimal_separator' => ',']);

        $size = FileSize::megabytes(1.5, ByteBase::Binary);

        expect($size->forHumans())->toBe('1,50 Mebibytes');
    });

    it('uses configured thousands separator', function (): void {
        config(['file-size.formatting.thousands_separator' => ' ']);

        $size = FileSize::bytes(1500000, ByteBase::Binary);

        expect($size->forHumans())->toContain(' ');
    });

    it('respects space between value and unit setting', function (): void {
        config(['file-size.formatting.space_between_value_and_unit' => false]);

        $size = FileSize::megabytes(1.5, ByteBase::Binary);

        expect($size->forHumans())->toBe('1.50Mebibytes');
    });
});

describe('label style configuration', function (): void {
    it('follows byte base when label_style is null', function (): void {
        config(['file-size.formatting.label_style' => null]);

        $binarySize = FileSize::megabytes(1.5, ByteBase::Binary);
        $decimalSize = FileSize::megabytes(1.5, ByteBase::Decimal);

        expect($binarySize->forHumans())->toBe('1.50 Mebibytes');
        expect($decimalSize->forHumans())->toBe('1.50 Megabytes');
    });

    it('uses decimal labels with binary calculations when label_style is decimal', function (): void {
        config(['file-size.formatting.label_style' => 'decimal']);

        // Binary calculations (1024-based) but with decimal labels
        $size = FileSize::megabytes(1.5, ByteBase::Binary);

        expect($size->forHumans())->toBe('1.50 Megabytes');
        expect($size->forHumans(short: true))->toBe('1.50 MB');
        // Verify calculation is still binary (1.5 MB = 1.5 * 1024 * 1024 bytes)
        expect($size->toBytes())->toBe(1572864.0);
    });

    it('uses binary labels with decimal calculations when label_style is binary', function (): void {
        config(['file-size.formatting.label_style' => 'binary']);

        // Decimal calculations (1000-based) but with binary labels
        $size = FileSize::megabytes(1.5, ByteBase::Decimal);

        expect($size->forHumans())->toBe('1.50 Mebibytes');
        expect($size->forHumans(short: true))->toBe('1.50 MiB');
        // Verify calculation is still decimal (1.5 MB = 1.5 * 1000 * 1000 bytes)
        expect($size->toBytes())->toBe(1500000.0);
    });

    it('applies label_style to short format', function (): void {
        config(['file-size.formatting.label_style' => 'decimal']);

        $size = FileSize::gigabytes(2, ByteBase::Binary);

        expect($size->formatShort())->toBe('2.00 GB');
    });

    it('applies label_style across all unit sizes', function (): void {
        config(['file-size.formatting.label_style' => 'decimal']);

        expect(FileSize::bytes(512, ByteBase::Binary)->forHumans())->toBe('512.00 Bytes');
        expect(FileSize::kilobytes(1.5, ByteBase::Binary)->forHumans())->toBe('1.50 Kilobytes');
        expect(FileSize::megabytes(1.5, ByteBase::Binary)->forHumans())->toBe('1.50 Megabytes');
        expect(FileSize::gigabytes(1.5, ByteBase::Binary)->forHumans())->toBe('1.50 Gigabytes');
        expect(FileSize::terabytes(1.5, ByteBase::Binary)->forHumans())->toBe('1.50 Terabytes');
        expect(FileSize::petabytes(1.5, ByteBase::Binary)->forHumans())->toBe('1.50 Petabytes');
    });
});
