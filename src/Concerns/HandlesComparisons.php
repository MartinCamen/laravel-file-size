<?php

namespace MartinCamen\FileSize\Concerns;

use MartinCamen\FileSize\Configuration\FileSizeConfiguration;
use MartinCamen\FileSize\Enums\Unit;
use MartinCamen\FileSize\FileSize;

trait HandlesComparisons
{
    public function equals(int|float $value, Unit $unit, ?int $precision = null): bool
    {
        return $this->compare($value, $unit, $precision) === 0;
    }

    public function notEquals(int|float $value, Unit $unit, ?int $precision = null): bool
    {
        return ! $this->equals($value, $unit, $precision);
    }

    public function greaterThan(int|float $value, Unit $unit, ?int $precision = null): bool
    {
        return $this->compare($value, $unit, $precision) > 0;
    }

    public function greaterThanOrEqual(int|float $value, Unit $unit, ?int $precision = null): bool
    {
        return $this->compare($value, $unit, $precision) >= 0;
    }

    public function lessThan(int|float $value, Unit $unit, ?int $precision = null): bool
    {
        return $this->compare($value, $unit, $precision) < 0;
    }

    public function lessThanOrEqual(int|float $value, Unit $unit, ?int $precision = null): bool
    {
        return $this->compare($value, $unit, $precision) <= 0;
    }

    public function between(int|float $min, int|float $max, Unit $unit, ?int $precision = null): bool
    {
        return $this->greaterThanOrEqual($min, $unit, $precision)
            && $this->lessThanOrEqual($max, $unit, $precision);
    }

    public function min(FileSize $other): self
    {
        return $this->bytes <= $other->bytes ? $this : $other;
    }

    public function max(FileSize $other): self
    {
        return $this->bytes >= $other->bytes ? $this : $other;
    }

    public function isZero(?int $precision = null): bool
    {
        return $this->equals(0, Unit::Byte, $precision);
    }

    public function isPositive(): bool
    {
        return $this->bytes > 0;
    }

    public function isNegative(): bool
    {
        return $this->bytes < 0;
    }

    private function compare(int|float $value, Unit $unit, ?int $precision = null): int
    {
        $precision ??= $this->precision ?? app(FileSizeConfiguration::class)->precision;

        $thisValue = round($this->bytes, $precision);
        $compareValue = round($unit->toBytes($value, $this->byteBase), $precision);

        return $thisValue <=> $compareValue;
    }
}
