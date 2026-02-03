<?php

declare(strict_types=1);

use MartinCamen\FileSize\Facades\FileSize;
use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\ConfigurationOption;

describe('ByteBase enum', function (): void {
    it('has binary multiplier of 1024', function (): void {
        expect(ByteBase::Binary->multiplier())->toBe(1024.0);
    });

    it('has decimal multiplier of 1000', function (): void {
        expect(ByteBase::Decimal->multiplier())->toBe(1000.0);
    });

    it('multiplies correctly for binary', function (): void {
        expect(ByteBase::Binary->multiply(2))->toBe(1048576.0); // 1024^2
    });

    it('multiplies correctly for decimal', function (): void {
        expect(ByteBase::Decimal->multiply(2))->toBe(1000000.0); // 1000^2
    });

    it('has default value', function (): void {
        expect(ByteBase::default())->toBe(ByteBase::Binary);
    });
});

describe('ByteBase switching', function (): void {
    it('converts correctly with binary base', function (): void {
        $size = FileSize::megabytes(1, [ConfigurationOption::ByteBase->value => ByteBase::Binary]);

        expect($size->toKilobytes())
            ->toBe(1024.0)
            ->and($size->toBytes())
            ->toBe(1048576.0);
    });

    it('converts correctly with decimal base', function (): void {
        $size = FileSize::megabytes(1, [ConfigurationOption::ByteBase->value => ByteBase::Decimal]);

        expect($size->toKilobytes())
            ->toBe(1000.0)
            ->and($size->toBytes())
            ->toBe(1000000.0);
    });

    it('can change byte base fluently', function (): void {
        $size = FileSize::megabytes(1)
            ->inBinaryFormat()
            ->byteBase(ByteBase::Decimal);

        expect($size->getByteBase())->toBe(ByteBase::Decimal);
    });

    it('preserves original when changing byte base', function (): void {
        $original = FileSize::megabytes(1);
        $changed = $original->byteBase(ByteBase::Decimal);

        expect($original->getByteBase())
            ->toBe(ByteBase::Binary)
            ->and($changed->getByteBase())
            ->toBe(ByteBase::Decimal);
    });

    it('uses configuration byte base when not specified', function (): void {
        config(['file-size.byte_base' => 'decimal']);

        $size = FileSize::megabytes(1);

        expect($size->getByteBase())->toBe(ByteBase::Decimal);
    });
});

describe('labels with different byte bases', function (): void {
    it('default uses decimal labels for binary base', function (): void {
        $size = FileSize::inBinaryFormat()->megabytes(1);

        expect($size->forHumans())
            ->toContain('Megabytes')
            ->and($size->formatShort())
            ->toContain('MB');
    });

    it('can use binary labels for binary base', function (): void {
        $size = FileSize::inBinaryFormat()->withBinaryLabel()->megabytes(1);

        expect($size->forHumans())
            ->toContain('Mebibytes')
            ->and($size->formatShort())
            ->toContain('MiB');
    });

    it('can use decimal labels with decimal base', function (): void {
        $size = FileSize::inDecimalFormat()->megabytes(1);

        expect($size->forHumans())
            ->toContain('Megabytes')
            ->and($size->formatShort())
            ->toContain('MB');
    });
});
