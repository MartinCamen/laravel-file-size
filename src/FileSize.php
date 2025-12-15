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

    private float $bytes = 0;
    private ByteBase $byteBase;

    public function __construct(?float $bytes = null, ?ByteBase $byteBase = null, private ?int $precision = null)
    {
        if ($bytes !== null) {
            $this->validateValue($bytes);
            $this->bytes = $bytes;
        }

        $this->byteBase = $byteBase ?? app(FileSizeConfiguration::class)->byteBase();
    }

    public function bytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        $base = $byteBase ?? $this->byteBase;

        return new self(
            Unit::Byte->toBytes($value, $base),
            $base,
        );
    }

    public function kilobytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        $base = $byteBase ?? $this->byteBase;

        return new self(
            Unit::KiloByte->toBytes($value, $base),
            $base,
        );
    }

    public function megabytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        $base = $byteBase ?? $this->byteBase;

        return new self(
            Unit::MegaByte->toBytes($value, $base),
            $base,
        );
    }

    public function gigabytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        $base = $byteBase ?? $this->byteBase;

        return new self(
            Unit::GigaByte->toBytes($value, $base),
            $base,
        );
    }

    public function terabytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        $base = $byteBase ?? $this->byteBase;

        return new self(
            Unit::TeraByte->toBytes($value, $base),
            $base,
        );
    }

    public function petabytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        $base = $byteBase ?? $this->byteBase;

        return new self(
            Unit::PetaByte->toBytes($value, $base),
            $base,
        );
    }

    // Singular forms (default to 1)
    public function byte(?ByteBase $byteBase = null): self
    {
        return $this->bytes(1, $byteBase);
    }

    public function kilobyte(?ByteBase $byteBase = null): self
    {
        return $this->kilobytes(1, $byteBase);
    }

    public function megabyte(?ByteBase $byteBase = null): self
    {
        return $this->megabytes(1, $byteBase);
    }

    public function gigabyte(?ByteBase $byteBase = null): self
    {
        return $this->gigabytes(1, $byteBase);
    }

    public function terabyte(?ByteBase $byteBase = null): self
    {
        return $this->terabytes(1, $byteBase);
    }

    public function petabyte(?ByteBase $byteBase = null): self
    {
        return $this->petabytes(1, $byteBase);
    }

    // Getters via magic method
    public function __get(string $property): float|int
    {
        $unit = $this->propertyToUnit($property);
        $value = $unit->fromBytes($this->bytes, $this->byteBase);

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
