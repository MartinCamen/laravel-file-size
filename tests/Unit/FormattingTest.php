<?php

declare(strict_types=1);

use MartinCamen\FileSize\Enums\ByteBase;
use MartinCamen\FileSize\FileSize;

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
