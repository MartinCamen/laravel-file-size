<?php

namespace MartinCamen\FileSize\Configuration;

use MartinCamen\PhpFileSize\Configuration\FileSizeOptions;

/** @phpstan-import-type OptionalFileSizeOptionsType from FileSizeOptions */
class FileSizeConfigurationOptions
{
    /** @param OptionalFileSizeOptionsType $options */
    public static function make(array $data = []): FileSizeOptions
    {
        return new FileSizeOptions(
            byteBase: data_get($data, 'byte_base', config()->string('file-size.byte_base')) ?: null,
            precision: (int) data_get($data, 'precision', config()->integer('file-size.precision')),
            labelStyle: data_get($data, 'label_style', config('file-size.formatting.label_style')) ?: null,
            decimalSeparator: data_get($data, 'decimal_separator', config()->string('file-size.formatting.decimal_separator')),
            thousandsSeparator: data_get($data, 'thousands_separator', config()->string('file-size.formatting.thousands_separator')),
            spaceBetweenValueAndUnit: (bool) data_get($data, 'space_between_value_and_unit', config()->boolean('file-size.formatting.space_between_value_and_unit')),
            validationThrowOnNegativeResult: (bool) data_get($data, 'throw_on_negative_result', config()->boolean('file-size.validation.throw_on_negative_result')),
            validationAllowNegativeInput: (bool) data_get($data, 'allow_negative_input', config()->boolean('file-size.validation.allow_negative_input')),
        );
    }
}
