<?php

declare(strict_types=1);

use MartinCamen\FileSize\Exceptions\InvalidValueException;
use MartinCamen\FileSize\Exceptions\NegativeValueException;
use MartinCamen\FileSize\Facades\FileSize;

describe('negative input validation', function (): void {
    it('throws on negative input by default', function (): void {
        FileSize::megabytes(-5);
    })->throws(NegativeValueException::class, 'Negative values are not allowed');

    it('allows negative input when configured', function (): void {
        config(['file-size.validation.allow_negative_input' => true]);

        $size = FileSize::megabytes(-5);

        expect($size->toMegabytes())->toBe(-5.0);
    });
});

describe('invalid value validation', function (): void {
    it('throws on infinity', function (): void {
        FileSize::bytes(INF);
    })->throws(InvalidValueException::class, 'Value must be a finite number');

    it('throws on negative infinity', function (): void {
        config(['file-size.validation.allow_negative_input' => true]);

        FileSize::bytes(-INF);
    })->throws(InvalidValueException::class);

    it('throws on NaN', function (): void {
        FileSize::bytes(NAN);
    })->throws(InvalidValueException::class, 'Value must be a finite number');
});

describe('divide by zero', function (): void {
    it('throws when dividing by zero integer', function (): void {
        FileSize::megabytes(100)->divide(0);
    })->throws(InvalidValueException::class, 'Cannot divide by zero');

    it('throws when dividing by zero float', function (): void {
        FileSize::megabytes(100)->divide(0.0);
    })->throws(InvalidValueException::class, 'Cannot divide by zero');
});

describe('property access validation', function (): void {
    it('throws on unknown property', function (): void {
        $size = FileSize::megabytes(5);
        $size->unknownProperty;
    })->throws(InvalidValueException::class, 'Unknown property');
});
