<?php

declare(strict_types=1);

use MartinCamen\FileSize\Facades\FileSize;
use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\Unit;
use MartinCamen\PhpFileSize\Exceptions\InvalidValueException;
use MartinCamen\PhpFileSize\Exceptions\NegativeValueException;

describe('add operations', function (): void {
    it('adds bytes to file size', function (): void {
        $size = FileSize::kilobytes(1)->addBytes(512);

        expect($size->toBytes())->toBe(1536.0);
    });

    it('adds kilobytes to file size', function (): void {
        $size = FileSize::megabytes(1)->addKilobytes(512);

        expect($size->toKilobytes())->toBe(1536.0);
    });

    it('adds megabytes to file size', function (): void {
        $size = FileSize::gigabytes(1)->addMegabytes(512);

        expect($size->toMegabytes())->toBe(1536.0);
    });

    it('adds gigabytes to file size', function (): void {
        $size = FileSize::terabytes(1)->addGigabytes(512);

        expect($size->toGigabytes())->toBe(1536.0);
    });

    it('adds terabytes to file size', function (): void {
        $size = FileSize::petabytes(1)->addTerabytes(512);

        expect($size->toTerabytes())->toBe(1536.0);
    });

    it('adds petabytes to file size', function (): void {
        $size = FileSize::petabytes(1)->addPetabytes(1);

        expect($size->toPetabytes())->toBe(2.0);
    });

    it('adds using generic add method with unit', function (): void {
        $size = FileSize::megabytes(1)->add(512, Unit::KiloByte);

        expect($size->toKilobytes())->toBe(1536.0);
    });
});

describe('subtract operations', function (): void {
    it('subtracts bytes from file size', function (): void {
        $size = FileSize::kilobytes(1)->subBytes(512);

        expect($size->toBytes())->toBe(512.0);
    });

    it('subtracts kilobytes from file size', function (): void {
        $size = FileSize::megabytes(1)->subKilobytes(512);

        expect($size->toKilobytes())->toBe(512.0);
    });

    it('subtracts megabytes from file size', function (): void {
        $size = FileSize::gigabytes(1)->subMegabytes(512);

        expect($size->toMegabytes())->toBe(512.0);
    });

    it('subtracts gigabytes from file size', function (): void {
        $size = FileSize::terabytes(1)->subGigabytes(512);

        expect($size->toGigabytes())->toBe(512.0);
    });

    it('subtracts terabytes from file size', function (): void {
        $size = FileSize::petabytes(1)->subTerabytes(512);

        expect($size->toTerabytes())->toBe(512.0);
    });

    it('subtracts petabytes from file size', function (): void {
        $size = FileSize::petabytes(2)->subPetabytes(1);

        expect($size->toPetabytes())->toBe(1.0);
    });

    it('subtracts using generic sub method with unit', function (): void {
        $size = FileSize::megabytes(1)->sub(512, Unit::KiloByte);

        expect($size->toKilobytes())->toBe(512.0);
    });

    it('allows negative results when configured', function (): void {
        config(['file-size.validation.allow_negative_input' => true]);

        $size = FileSize::megabytes(1)->subMegabytes(2);

        expect($size->toMegabytes())->toBe(-1.0);
        expect($size->isNegative())->toBeTrue();
    });

    it('throws when negative results are not allowed', function (): void {
        config(['file-size.validation.throw_on_negative_result' => true]);

        FileSize::megabytes(1)->subMegabytes(2)->toMegabytes();
    })->throws(NegativeValueException::class);
});

describe('multiply operations', function (): void {
    it('multiplies file size by integer', function (): void {
        $size = FileSize::megabytes(5)->multiply(3);

        expect($size->toMegabytes())->toBe(15.0);
    });

    it('multiplies file size by float', function (): void {
        $size = FileSize::megabytes(10)->multiply(1.5);

        expect($size->toMegabytes())->toBe(15.0);
    });

    it('multiplies by zero results in zero', function (): void {
        $size = FileSize::megabytes(100)->multiply(0);

        expect($size->toMegabytes())->toBe(0.0);
        expect($size->isZero())->toBeTrue();
    });
});

describe('divide operations', function (): void {
    it('divides file size by integer', function (): void {
        $size = FileSize::megabytes(15)->divide(3);

        expect($size->toMegabytes())->toBe(5.0);
    });

    it('divides file size by float', function (): void {
        $size = FileSize::megabytes(15)->divide(1.5);

        expect($size->toMegabytes())->toBe(10.0);
    });

    it('throws when dividing by zero', function (): void {
        FileSize::megabytes(100)->divide(0);
    })->throws(InvalidValueException::class, 'Cannot divide by zero.');
});

describe('abs operation', function (): void {
    it('returns absolute value of positive size', function (): void {
        $size = FileSize::megabytes(5)->abs();

        expect($size->toMegabytes())->toBe(5.0);
    });

    it('returns absolute value of negative size', function (): void {
        config(['file-size.validation.allow_negative_input' => true]);

        $size = FileSize::megabytes(1)
            ->subMegabytes(6)
            ->abs();

        expect($size->toMegabytes())->toBe(5.0);
        expect($size->isPositive())->toBeTrue();
    });
});

describe('method chaining', function (): void {
    it('chains multiple arithmetic operations', function (): void {
        $size = FileSize::megabytes(10)
            ->addMegabytes(5)
            ->subMegabytes(3)
            ->multiply(2)
            ->divide(4);

        expect($size->toMegabytes())->toBe(6.0);
    });

    it('preserves byte base through chained operations', function (): void {
        $size = FileSize::megabytes(10)
            ->inDecimalFormat()
            ->addMegabytes(5)
            ->subMegabytes(3);

        expect($size->getByteBase())->toBe(ByteBase::Decimal);
    });
});
