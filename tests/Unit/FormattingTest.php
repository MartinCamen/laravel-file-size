<?php

declare(strict_types=1);

use MartinCamen\FileSize\Facades\FileSize;
use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\ConfigurationOption;

describe('forHumans', function (): void {
    it('formats bytes', function (): void {
        $size = FileSize::bytes(512);

        expect($size->forHumans())->toBe('512.00 Bytes');
    });

    it('formats kilobytes', function (): void {
        $size = FileSize::kilobytes(1.5);

        expect($size->forHumans())->toBe('1.50 Kilobytes');
    });

    it('formats megabytes', function (): void {
        $size = FileSize::megabytes(1.5);

        expect($size->forHumans())->toBe('1.50 Megabytes');
    });

    it('formats gigabytes', function (): void {
        $size = FileSize::gigabytes(2.5);

        expect($size->forHumans())->toBe('2.50 Gigabytes');
    });

    it('formats terabytes', function (): void {
        $size = FileSize::terabytes(1.25);

        expect($size->forHumans())->toBe('1.25 Terabytes');
    });

    it('formats petabytes', function (): void {
        $size = FileSize::petabytes(1.75);

        expect($size->forHumans())->toBe('1.75 Petabytes');
    });

    it('uses short labels', function (): void {
        $size = FileSize::megabytes(1.5);

        expect($size->forHumans(short: true))->toBe('1.50 MB');
    });

    it('uses custom precision', function (): void {
        $size = FileSize::megabytes(1.5678);

        expect($size->precision(3)->forHumans())->toBe('1.568 Megabytes');
    });

    it('uses decimal labels when implicitly defined', function (): void {
        expect(FileSize::inDecimalFormat()->megabytes(1.5)->forHumans())->toBe('1.50 Megabytes');
    });

    it('uses short decimal labels  when implicitly defined', function (): void {
        expect(FileSize::inDecimalFormat()->megabytes(1.5)->forHumans(short: true))->toBe('1.50 MB');
    });

    it('uses binary labels for binary base when implicitly defined', function (): void {
        expect(FileSize::inBinaryFormat()->megabytes(1.5)->withBinaryLabel()->forHumans())->toBe('1.50 Mebibytes');
    });

    it('uses short binary labels when implicitly defined', function (): void {
        expect(FileSize::inBinaryFormat()->megabytes(1.5)->withBinaryLabel()->forHumans(short: true))->toBe('1.50 MiB');
    });

    it('accepts binary label byte base for binary byte base', function (): void {
        $size = FileSize::megabytes(2.52, [ConfigurationOption::ByteBase->value => ByteBase::Binary->value]);

        expect($size->withBinaryLabel()->forHumans())->toBe('2.52 Mebibytes');
    });

    it('accepts decimal label byte base for binary byte base', function (): void {
        $size = FileSize::megabytes(2.52, [ConfigurationOption::ByteBase->value => ByteBase::Binary->value]);

        expect($size->withDecimalLabel()->forHumans())->toBe('2.52 Megabytes');
    });

    it('accepts binary label byte base for decimal byte base', function (): void {
        $size = FileSize::inDecimalFormat()->megabytes(2.52);

        expect($size->withBinaryLabel()->forHumans())->toBe('2.52 Mebibytes');
    });

    it('accepts decimal label byte base for decimal byte base', function (): void {
        $size = FileSize::inDecimalFormat()->megabytes(2.52);

        expect($size->withDecimalLabel()->forHumans())->toBe('2.52 Megabytes');
    });
});

describe('format alias', function (): void {
    it('formats with long labels', function (): void {
        $size = FileSize::megabytes(2.5);

        expect($size->format())->toBe('2.50 Megabytes');
    });

    it('accepts precision parameter', function (): void {
        $size = FileSize::megabytes(2.567);

        expect($size->precision(1)->format())->toBe('2.6 Megabytes');
    });

    it('accepts binary label byte base for binary byte base', function (): void {
        $size = FileSize::megabytes(2.52);

        expect($size->withBinaryLabel()->format())->toBe('2.52 Mebibytes');
    });

    it('accepts decimal label byte base for binary byte base', function (): void {
        $size = FileSize::megabytes(2.52);

        expect($size->withDecimalLabel()->format())->toBe('2.52 Megabytes');
    });

    it('accepts binary label byte base for decimal byte base', function (): void {
        $size = FileSize::inDecimalFormat()->megabytes(2.52);

        expect($size->withBinaryLabel()->format())->toBe('2.52 Mebibytes');
    });

    it('accepts decimal label byte base for decimal byte base', function (): void {
        $size = FileSize::inDecimalFormat()->megabytes(2.52);

        expect($size->withDecimalLabel()->format())->toBe('2.52 Megabytes');
    });
});

describe('formatShort alias', function (): void {
    it('formats with short labels', function (): void {
        $size = FileSize::megabytes(2.5);

        expect($size->formatShort())->toBe('2.50 MB');
    });

    it('accepts precision parameter', function (): void {
        $size = FileSize::megabytes(2.567);

        expect($size->precision(1)->formatShort())->toBe('2.6 MB');
    });
});

describe('best unit selection', function (): void {
    it('selects bytes for small values', function (): void {
        $size = FileSize::bytes(512);

        expect($size->forHumans())->toContain('Bytes');
    });

    it('selects kilobytes for KB range', function (): void {
        $size = FileSize::bytes(2048);

        expect($size->forHumans())->toContain('Kilobytes');
    });

    it('selects megabytes for MB range', function (): void {
        $size = FileSize::kilobytes(2048);

        expect($size->forHumans())->toContain('Megabytes');
    });

    it('selects gigabytes for GB range', function (): void {
        $size = FileSize::megabytes(2048);

        expect($size->forHumans())->toContain('Gigabytes');
    });

    it('selects terabytes for TB range', function (): void {
        $size = FileSize::gigabytes(2048);

        expect($size->forHumans())->toContain('Terabytes');
    });

    it('selects petabytes for PB range', function (): void {
        $size = FileSize::terabytes(2048);

        expect($size->forHumans())->toContain('Petabytes');
    });
});

describe('configuration options', function (): void {
    it('uses configured decimal separator', function (): void {
        config(['file-size.formatting.decimal_separator' => ',']);

        $size = FileSize::megabytes(1.5);

        expect($size->forHumans())->toBe('1,50 Megabytes');
    });

    it('uses configured thousands separator', function (): void {
        config(['file-size.formatting.thousands_separator' => ' ']);

        $size = FileSize::bytes(1500000);

        expect($size->forHumans())->toContain(' ');
    });

    it('respects space between value and unit setting', function (): void {
        config(['file-size.formatting.space_between_value_and_unit' => false]);

        $size = FileSize::megabytes(1.5);

        expect($size->forHumans())->toBe('1.50Megabytes');
    });
});

describe('label style configuration', function (): void {
    it('follows byte base when label_style is null', function (): void {
        config(['file-size.formatting.label_style' => null]);

        $binarySize = FileSize::megabytes(1.5);
        $decimalSize = FileSize::megabytes(1.5)->inDecimalFormat();

        expect($binarySize->forHumans())->toBe('1.50 Mebibytes');
        expect($decimalSize->forHumans())->toBe('1.50 Megabytes');
    });

    it('uses decimal labels with binary calculations when label_style is decimal', function (): void {
        config(['file-size.formatting.label_style' => 'decimal']);

        // Binary calculations (1024-based) but with decimal labels
        $size = FileSize::megabytes(1.5);

        expect($size->forHumans())->toBe('1.50 Megabytes');
        expect($size->forHumans(short: true))->toBe('1.50 MB');
        // Verify calculation is still binary (1.5 MB = 1.5 * 1024 * 1024 bytes)
        expect($size->toBytes())->toBe(1572864.0);
    });

    it('uses binary labels with decimal calculations when label_style is binary', function (): void {
        config(['file-size.formatting.label_style' => 'binary']);

        // Decimal calculations (1000-based) but with binary labels
        $size = FileSize::megabytes(1.5)->inDecimalFormat();

        expect($size->forHumans())->toBe('1.50 Mebibytes');
        expect($size->forHumans(short: true))->toBe('1.50 MiB');
        // Verify calculation is still decimal (1.5 MB = 1.5 * 1000 * 1000 bytes)
        expect($size->toBytes())->toBe(1500000.0);
    });

    it('applies label_style to short format', function (): void {
        config(['file-size.formatting.label_style' => 'decimal']);

        $size = FileSize::gigabytes(2);

        expect($size->formatShort())->toBe('2.00 GB');
    });

    it('applies label_style across all unit sizes', function (): void {
        config(['file-size.formatting.label_style' => 'decimal']);

        expect(FileSize::bytes(512)->forHumans())->toBe('512.00 Bytes');
        expect(FileSize::kilobytes(1.5)->forHumans())->toBe('1.50 Kilobytes');
        expect(FileSize::megabytes(1.5)->forHumans())->toBe('1.50 Megabytes');
        expect(FileSize::gigabytes(1.5)->forHumans())->toBe('1.50 Gigabytes');
        expect(FileSize::terabytes(1.5)->forHumans())->toBe('1.50 Terabytes');
        expect(FileSize::petabytes(1.5)->forHumans())->toBe('1.50 Petabytes');
    });
});
