<?php

namespace MartinCamen\FileSize\Concerns;

use MartinCamen\FileSize\Configuration\FileSizeConfiguration;
use MartinCamen\FileSize\Enums\Unit;

trait HandlesConversions
{
    public function toBytes(?int $precision = null): float|int
    {
        return $this->convertTo(Unit::Byte, $precision);
    }

    public function toKilobytes(?int $precision = null): float|int
    {
        return $this->convertTo(Unit::KiloByte, $precision);
    }

    public function toMegabytes(?int $precision = null): float|int
    {
        return $this->convertTo(Unit::MegaByte, $precision);
    }

    public function toGigabytes(?int $precision = null): float|int
    {
        return $this->convertTo(Unit::GigaByte, $precision);
    }

    public function toTerabytes(?int $precision = null): float|int
    {
        return $this->convertTo(Unit::TeraByte, $precision);
    }

    public function toPetabytes(?int $precision = null): float|int
    {
        return $this->convertTo(Unit::PetaByte, $precision);
    }

    private function convertTo(Unit $unit, ?int $precision = null): float|int
    {
        $value = $unit->fromBytes($this->bytes, $this->byteBase);
        $precision ??= $this->precision ?? app(FileSizeConfiguration::class)->precision;

        return round($value, $precision);
    }
}
