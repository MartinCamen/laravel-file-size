<?php

namespace MartinCamen\FileSize\Enums;

use MartinCamen\FileSize\Configuration\FileSizeConfiguration;

enum ByteBase: string
{
    case Binary = 'binary';  // IEC standard (KiB, MiB, GiB)
    case Decimal = 'decimal'; // SI standard (KB, MB, GB)

    public static function default(): self
    {
        return self::Decimal;
    }

    public static function fromConfig(): self
    {
        return app(FileSizeConfiguration::class)->byteBase();
    }

    public function multiplier(): float
    {
        return match ($this) {
            self::Binary  => 1024,
            self::Decimal => 1000,
        };
    }

    public function multiply(int $exponent): float
    {
        return $this->multiplier() ** $exponent;
    }
}
