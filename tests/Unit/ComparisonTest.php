<?php

declare(strict_types=1);

use MartinCamen\FileSize\Enums\ByteBase;
use MartinCamen\FileSize\Enums\Unit;
use MartinCamen\FileSize\Facades\FileSize;

describe('equals', function (): void {
    it('returns true when values are equal', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->equals(2048, Unit::KiloByte))->toBeTrue();
    });

    it('returns false when values are not equal', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->equals(1000, Unit::KiloByte))->toBeFalse();
    });

    it('compares with precision', function (): void {
        $size = FileSize::bytes(1024, ByteBase::Binary);

        expect($size->equals(1, Unit::KiloByte, precision: 0))->toBeTrue();
    });
});

describe('notEquals', function (): void {
    it('returns true when values are not equal', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->notEquals(1000, Unit::KiloByte))->toBeTrue();
    });

    it('returns false when values are equal', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->notEquals(2048, Unit::KiloByte))->toBeFalse();
    });
});

describe('greaterThan', function (): void {
    it('returns true when size is greater', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->greaterThan(1, Unit::MegaByte))->toBeTrue();
    });

    it('returns false when size is equal', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->greaterThan(2, Unit::MegaByte))->toBeFalse();
    });

    it('returns false when size is less', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->greaterThan(3, Unit::MegaByte))->toBeFalse();
    });
});

describe('greaterThanOrEqual', function (): void {
    it('returns true when size is greater', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->greaterThanOrEqual(1, Unit::MegaByte))->toBeTrue();
    });

    it('returns true when size is equal', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->greaterThanOrEqual(2, Unit::MegaByte))->toBeTrue();
    });

    it('returns false when size is less', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->greaterThanOrEqual(3, Unit::MegaByte))->toBeFalse();
    });
});

describe('lessThan', function (): void {
    it('returns true when size is less', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->lessThan(3, Unit::MegaByte))->toBeTrue();
    });

    it('returns false when size is equal', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->lessThan(2, Unit::MegaByte))->toBeFalse();
    });

    it('returns false when size is greater', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->lessThan(1, Unit::MegaByte))->toBeFalse();
    });
});

describe('lessThanOrEqual', function (): void {
    it('returns true when size is less', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->lessThanOrEqual(3, Unit::MegaByte))->toBeTrue();
    });

    it('returns true when size is equal', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->lessThanOrEqual(2, Unit::MegaByte))->toBeTrue();
    });

    it('returns false when size is greater', function (): void {
        $size = FileSize::megabytes(2, ByteBase::Binary);

        expect($size->lessThanOrEqual(1, Unit::MegaByte))->toBeFalse();
    });
});

describe('between', function (): void {
    it('returns true when value is within range', function (): void {
        $size = FileSize::megabytes(5, ByteBase::Binary);

        expect($size->between(1, 10, Unit::MegaByte))->toBeTrue();
    });

    it('returns true when value equals lower bound', function (): void {
        $size = FileSize::megabytes(1, ByteBase::Binary);

        expect($size->between(1, 10, Unit::MegaByte))->toBeTrue();
    });

    it('returns true when value equals upper bound', function (): void {
        $size = FileSize::megabytes(10, ByteBase::Binary);

        expect($size->between(1, 10, Unit::MegaByte))->toBeTrue();
    });

    it('returns false when value is below range', function (): void {
        $size = FileSize::megabytes(0, ByteBase::Binary);

        expect($size->between(1, 10, Unit::MegaByte))->toBeFalse();
    });

    it('returns false when value is above range', function (): void {
        $size = FileSize::megabytes(11, ByteBase::Binary);

        expect($size->between(1, 10, Unit::MegaByte))->toBeFalse();
    });
});

describe('min and max', function (): void {
    it('returns the smaller size with min', function (): void {
        $size1 = FileSize::megabytes(5, ByteBase::Binary);
        $size2 = FileSize::megabytes(10, ByteBase::Binary);

        expect($size1->min($size2))->toBe($size1);
        expect($size2->min($size1))->toBe($size1);
    });

    it('returns the larger size with max', function (): void {
        $size1 = FileSize::megabytes(5, ByteBase::Binary);
        $size2 = FileSize::megabytes(10, ByteBase::Binary);

        expect($size1->max($size2))->toBe($size2);
        expect($size2->max($size1))->toBe($size2);
    });

    it('returns self when sizes are equal', function (): void {
        $size1 = FileSize::megabytes(5, ByteBase::Binary);
        $size2 = FileSize::megabytes(5, ByteBase::Binary);

        expect($size1->min($size2))->toBe($size1);
        expect($size1->max($size2))->toBe($size1);
    });
});

describe('state checks', function (): void {
    it('isZero returns true for zero size', function (): void {
        $size = FileSize::bytes(0);

        expect($size->isZero())->toBeTrue();
    });

    it('isZero returns false for non-zero size', function (): void {
        $size = FileSize::bytes(1);

        expect($size->isZero())->toBeFalse();
    });

    it('isPositive returns true for positive size', function (): void {
        $size = FileSize::megabytes(5);

        expect($size->isPositive())->toBeTrue();
    });

    it('isPositive returns false for zero size', function (): void {
        $size = FileSize::bytes(0);

        expect($size->isPositive())->toBeFalse();
    });

    it('isNegative returns true for negative size', function (): void {
        config(['file-size.validation.allow_negative_input' => true]);

        $size = FileSize::megabytes(1, ByteBase::Binary)->subMegabytes(2);

        expect($size->isNegative())->toBeTrue();
    });

    it('isNegative returns false for positive size', function (): void {
        $size = FileSize::megabytes(5);

        expect($size->isNegative())->toBeFalse();
    });
});
