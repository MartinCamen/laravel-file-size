<?php

namespace MartinCamen\FileSize\Concerns;

use MartinCamen\FileSize\Configuration\FileSizeConfiguration;
use MartinCamen\FileSize\Enums\Unit;

trait HandlesFormatting
{
    public function forHumans(bool $short = false, ?int $precision = null): string
    {
        $configuration = app(FileSizeConfiguration::class);

        $precision ??= $this->precision ?? $configuration->precision;

        $unit = $this->guessUnit();
        $value = $unit->fromBytes($this->bytes, $this->byteBase);

        $formattedValue = number_format(
            round($value, $precision),
            $precision,
            $configuration->decimalSeparator,
            $configuration->thousandsSeparator
        );

        return str($formattedValue)
            ->append($configuration->spaceBetweenValueAndUnit ? ' ' : '')
            ->append($unit->label($this->byteBase, $short))
            ->value();
    }

    public function format(?int $precision = null): string
    {
        return $this->forHumans(false, $precision);
    }

    public function formatShort(?int $precision = null): string
    {
        return $this->forHumans(true, $precision);
    }

    private function guessUnit(): Unit
    {
        $absBytes = abs($this->bytes);

        foreach ([Unit::PetaByte, Unit::TeraByte, Unit::GigaByte, Unit::MegaByte, Unit::KiloByte] as $unit) {
            if ($absBytes >= $unit->toBytes(1, $this->byteBase)) {
                return $unit;
            }
        }

        return Unit::Byte;
    }
}
