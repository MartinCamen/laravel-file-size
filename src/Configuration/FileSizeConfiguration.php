<?php

namespace MartinCamen\FileSize\Configuration;

use Illuminate\Container\Attributes\Config;
use MartinCamen\FileSize\Enums\ByteBase;

class FileSizeConfiguration
{
    public function __construct(
        #[Config('file-size.byte_base')]
        public ?string $byteBase,
        #[Config('file-size.precision', 2)]
        public int $precision,
        #[Config('file-size.formatting.decimal_separator', '.')]
        public string $decimalSeparator,
        #[Config('file-size.formatting.thousands_separator', ',')]
        public string $thousandsSeparator,
        #[Config('file-size.formatting.space_between_value_and_unit', true)]
        public bool $spaceBetweenValueAndUnit,
        #[Config('file-size.validation.throw_on_negative_result', false)]
        public bool $validationThrowOnNegativeResult,
        #[Config('file-size.validation.allow_negative_input', false)]
        public bool $validationAllowNegativeInput,
    ) {}

    public function byteBase(): ByteBase
    {
        return $this->byteBase
            ? ByteBase::from($this->byteBase)
            : ByteBase::default();
    }
}
