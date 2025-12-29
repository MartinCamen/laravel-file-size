<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Byte Base System
    |--------------------------------------------------------------------------
    |
    | Choose between binary (1024-based) or decimal (1000-based) system.
    | Options: 'binary' (default) or 'decimal'
    |
    | Binary: 1 KB = 1024 bytes (KiB, MiB, GiB)
    | Decimal: 1 KB = 1000 bytes (KB, MB, GB)
    |
    */
    'byte_base'  => env('FILE_INFO_BYTE_BASE', MartinCamen\PhpFileSize\Enums\ByteBase::default()->value),

    /*
    |--------------------------------------------------------------------------
    | Default Precision
    |--------------------------------------------------------------------------
    |
    | Default number of decimal places for conversions.
    | Can be overridden per operation.
    |
    */
    'precision'  => 2,

    /*
    |--------------------------------------------------------------------------
    | Formatting Options
    |--------------------------------------------------------------------------
    |
    | Configuration for human-readable output
    |
    */
    'formatting' => [
        /*
        |--------------------------------------------------------------------------
        | Label Style
        |--------------------------------------------------------------------------
        |
        | Controls which naming convention to use for unit labels, independent of
        | the byte base used for calculations.
        |
        | Options:
        |   - 'decimal': Always use SI labels (Kilobytes, Megabytes, KB, MB)
        |   - 'binary': Always use IEC labels (Kibibytes, Mebibytes, KiB, MiB)
        |   - null (default): Labels follow the byte_base setting
        |
        */
        'label_style'                  => MartinCamen\PhpFileSize\Enums\ByteBase::Decimal->value,

        'decimal_separator'            => '.',
        'thousands_separator'          => ',',
        'space_between_value_and_unit' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    |
    | Configure validation behavior
    |
    */
    'validation' => [
        'allow_negative_input'     => false,
        'throw_on_negative_result' => false, // After subtraction
    ],
];
