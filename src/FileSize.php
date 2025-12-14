<?php

namespace MartinCamen\FileSize;

use MartinCamen\FileSize\Concerns\HandlesArithmetic;
use MartinCamen\FileSize\Concerns\HandlesComparisons;
use MartinCamen\FileSize\Concerns\HandlesConversions;
use MartinCamen\FileSize\Concerns\HandlesFormatting;
use MartinCamen\FileSize\Configuration\FileSizeConfiguration;
use MartinCamen\FileSize\Enums\ByteBase;
use MartinCamen\FileSize\Enums\Unit;
use MartinCamen\FileSize\Exceptions\InvalidValueException;
use MartinCamen\FileSize\Exceptions\NegativeValueException;

class FileSize
{
    use HandlesArithmetic;
    use HandlesComparisons;
    use HandlesConversions;
    use HandlesFormatting;

    private float $bytes;
    private ByteBase $byteBase;
    private ?int $precision = null;

    private function __construct(float $bytes, ?ByteBase $byteBase = null, ?int $precision = null)
    {
        $this->validateValue($bytes);

        $this->bytes = $bytes;
        $this->byteBase = $byteBase ?? app(FileSizeConfiguration::class)->byteBase();
        $this->precision = $precision;
    }

    public static function bytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        return new self(
            Unit::Byte->toBytes($value, $byteBase),
            $byteBase,
        );
    }

    public static function kilobytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        return new self(
            Unit::KiloByte->toBytes($value, $byteBase),
            $byteBase,
        );
    }

    public static function megabytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        return new self(
            Unit::MegaByte->toBytes($value, $byteBase),
            $byteBase,
        );
    }

    public static function gigabytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        return new self(
            Unit::GigaByte->toBytes($value, $byteBase),
            $byteBase,
        );
    }

    public static function terabytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        return new self(
            Unit::TeraByte->toBytes($value, $byteBase),
            $byteBase,
        );
    }

    public static function petabytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        return new self(
            Unit::PetaByte->toBytes($value, $byteBase),
            $byteBase,
        );
    }

    // Singular forms (default to 1)
    public static function byte(?ByteBase $byteBase = null): self
    {
        return self::bytes(1, $byteBase);
    }

    public static function kilobyte(?ByteBase $byteBase = null): self
    {
        return self::kilobytes(1, $byteBase);
    }

    public static function megabyte(?ByteBase $byteBase = null): self
    {
        return self::megabytes(1, $byteBase);
    }

    public static function gigabyte(?ByteBase $byteBase = null): self
    {
        return self::gigabytes(1, $byteBase);
    }

    public static function terabyte(?ByteBase $byteBase = null): self
    {
        return self::terabytes(1, $byteBase);
    }

    public static function petabyte(?ByteBase $byteBase = null): self
    {
        return self::petabytes(1, $byteBase);
    }

    // Getters via magic method
    public function __get(string $property): float|int
    {
        $unit = $this->propertyToUnit($property);
        $value = Unit::fromBytes($this->bytes, $this->byteBase);

        if ($this->precision !== null) {
            return round($value, $this->precision);
        }

        return $value;
    }

    // Precision configuration
    public function precision(int $precision): self
    {
        $clone = clone $this;
        $clone->precision = $precision;

        return $clone;
    }

    public function byteBase(ByteBase $byteBase): self
    {
        $clone = clone $this;
        $clone->byteBase = $byteBase;

        return $clone;
    }

    // Internal helpers
    private function validateValue(float $value): void
    {
        if ($value < 0
            && ! app(FileSizeConfiguration::class)->validationAllowNegativeInput
        ) {
            throw new NegativeValueException(
                'Negative values are not allowed. Use subtraction methods instead.',
            );
        }

        if (! is_finite($value)) {
            throw new InvalidValueException('Value must be a finite number.');
        }
    }

    private function propertyToUnit(string $property): Unit
    {
        return match (rtrim($property, 's')) {
            'bytes', 'byte'  => Unit::Byte,
            'kilobyte', 'kb' => Unit::KiloByte,
            'megabyte', 'mb' => Unit::MegaByte,
            'gigabyte', 'gb' => Unit::GigaByte,
            'terabyte', 'tb' => Unit::TeraByte,
            'petabyte', 'pb' => Unit::PetaByte,
            default          => throw new InvalidValueException("Unknown property: {$property}"),
        };
    }

    // Accessors for internal state
    public function getBytes(): float
    {
        return $this->bytes;
    }

    public function getByteBase(): ByteBase
    {
        return $this->byteBase;
    }

    public function getPrecision(): ?int
    {
        return $this->precision;
    }
}
