# Laravel File Size

A fluent, immutable API for converting, comparing, and formatting file sizes in Laravel with support for both binary (1024-based) and decimal (1000-based) byte systems.

## Requirements

- PHP 8.3+
- Laravel 12.x+

## Installation

```bash
composer require martincamen/laravel-file-size
```

The package will automatically register its service provider.

### Publish Configuration

```bash
php artisan vendor:publish --tag="file-size-config"
```

## Quick Start

```php
use MartinCamen\FileSize\FileSizerino;
use MartinCamen\FileSize\Enums\ByteBase;

// Create a file size
$size = FileSizerino::megabytes(5);

// Convert between units
$size->toKilobytes();    // 5120.0 (binary)
$size->toGigabytes();    // 0.00 (rounded to 2 decimals)
$size->toBytes();        // 5242880.0

// Format for display
$size->forHumans();                                // "5.00 Megabytes"
$size->formatShort(labelStyle: ByteBase::Decimal); // "5.00 MB"

// Arithmetic operations
$size->addMegabytes(2)->toMegabytes(); // 7.0
$size->multiply(2)->toMegabytes();    // 10.0

// Comparisons
$size->greaterThan(4, Unit::MegaByte); // true
$size->between(1, 10, Unit::MegaByte); // true
```

## Factory Methods

Create `FileSize` instances using static factory methods:

```php
// Plural forms (specify value)
FileSize::bytes(1024);
FileSize::kilobytes(5);
FileSize::megabytes(100);
FileSize::gigabytes(2.5);
FileSize::terabytes(1);
FileSize::petabytes(0.5);

// Singular forms (default to 1)
FileSize::byte();     // 1 byte
FileSize::kilobyte(); // 1 KB
FileSize::megabyte(); // 1 MB
FileSize::gigabyte(); // 1 GB
FileSize::terabyte(); // 1 TB
FileSize::petabyte(); // 1 PB
```

### Specifying Byte Base

```php
use MartinCamen\FileSize\Enums\ByteBase;

// Binary (1024-based) - default
FileSize::megabytes(1, ByteBase::Binary); // 1 MB = 1,048,576 bytes

// Decimal (1000-based)
FileSize::megabytes(1, ByteBase::Decimal); // 1 MB = 1,000,000 bytes
```

## Conversions

Convert to specific units:

```php
$size = FileSize::gigabytes(2.5, ByteBase::Binary);

$size->toBytes();     // 2684354560.0
$size->toKilobytes(); // 2621440.0
$size->toMegabytes(); // 2560.0
$size->toGigabytes(); // 2.5
$size->toTerabytes(); // 0.0
$size->toPetabytes(); // 0.0
```

### With Custom Precision

```php
$size = FileSize::bytes(1234567, ByteBase::Binary);

$size->toKilobytes(0); // 1206.0
$size->toKilobytes(2); // 1205.63
$size->toKilobytes(4); // 1205.6318
```

### Property Access

```php
$size = FileSize::megabytes(2);

$size->bytes;     // 2097152.0
$size->kilobytes; // 2048.0
$size->megabytes; // 2.0
```

## Arithmetic Operations

All operations return new immutable instances:

```php
$size = FileSize::megabytes(10);

// Addition
$size->addBytes(512);
$size->addKilobytes(100);
$size->addMegabytes(5);
$size->addGigabytes(1);
$size->addTerabytes(0.5);
$size->addPetabytes(0.1);

// Subtraction
$size->subBytes(512);
$size->subKilobytes(100);
$size->subMegabytes(5);
$size->subGigabytes(1);
$size->subTerabytes(0.5);
$size->subPetabytes(0.1);

// Multiplication and Division
$size->multiply(2); // 20 MB
$size->divide(4);   // 2.5 MB

// Absolute value
$size->subMegabytes(15)->abs(); // 5 MB
```

### Generic Add/Sub with Unit

```php
use MartinCamen\FileSize\Enums\Unit;

$size->add(100, Unit::KiloByte);
$size->sub(50, Unit::MegaByte);
```

### Method Chaining

```php
$size = FileSize::megabytes(100)
    ->addGigabytes(1)
    ->subMegabytes(50)
    ->multiply(2)
    ->divide(4);
```

## Comparisons

```php
use MartinCamen\FileSize\Enums\Unit;

$size = FileSize::megabytes(500);

// Equality
$size->equals(500, Unit::MegaByte);    // true
$size->notEquals(400, Unit::MegaByte); // true

// Greater than
$size->greaterThan(400, Unit::MegaByte);        // true
$size->greaterThanOrEqual(500, Unit::MegaByte); // true

// Less than
$size->lessThan(600, Unit::MegaByte);        // true
$size->lessThanOrEqual(500, Unit::MegaByte); // true

// Range check
$size->between(100, 1000, Unit::MegaByte); // true
```

### Min/Max

```php
$size1 = FileSize::megabytes(100);
$size2 = FileSize::megabytes(200);

$size1->min($size2); // Returns $size1 (100 MB)
$size1->max($size2); // Returns $size2 (200 MB)
```

### State Checks

```php
$size->isZero();     // true if 0 bytes
$size->isPositive(); // true if > 0
$size->isNegative(); // true if < 0
```

## Formatting

```php
$size = FileSize::megabytes(1.5);

// Full labels
$size->forHumans(); // "1.50 Megabytes"
$size->format();    // "1.50 Megabytes"

// Short labels
$size->forHumans(short: true); // "1.50 MB"
$size->formatShort();          // "1.50 MB"

// Custom precision
$size->forHumans(precision: 0); // "2 Megabytes"
$size->forHumans(precision: 4); // "1.5000 Megabytes"

// Labels with specific label formatting
$size->forHumans(labelStyle: ByteBase::Decimal);       // "1.50 Megabytes" (default)
$size->forHumans(labelStyle: ByteBase::Binary);        // "1.50 Mebibytes"
$size->format(labelStyle: ByteBase::Decimal);          // "1.50 Megabytes" (default)
$size->format(labelStyle: ByteBase::Binary);           // "1.50 Mebibytes"
```

### Automatic Unit Selection

The formatter automatically selects the most appropriate unit:

```php
FileSize::bytes(500)->forHumans();     // "500.00 Bytes"
FileSize::kilobytes(1.5)->forHumans(); // "1.50 Kilobytes"
FileSize::megabytes(2.5)->forHumans(); // "2.50 Megabytes"
FileSize::gigabytes(1.25)->forHumans();// "1.25 Gigabytes"
```

### Decimal vs Binary Labels

```php
// Binary base (default)
FileSize::megabytes(1, ByteBase::Binary)->forHumans();   // "1.00 Megabytes"
FileSize::megabytes(1, ByteBase::Binary)->formatShort(); // "1.00 MB"
// Binary base with Decimal labels
FileSize::megabytes(1, ByteBase::Binary)->forHumans(labelStyle: ByteBase::Decimal);   // "1.00 Megabytes"
FileSize::megabytes(1, ByteBase::Binary)->formatShort(labelStyle: ByteBase::Decimal); // "1.00 MB"

// Decimal base
FileSize::megabytes(1, ByteBase::Decimal)->forHumans();   // "1.00 Megabytes"
FileSize::megabytes(1, ByteBase::Decimal)->formatShort(); // "1.00 MB"
// Decimal base with Binary labels
FileSize::megabytes(1, ByteBase::Decimal)->forHumans(labelStyle: ByteBase::Binary);   // "1.00 Megabytes"
FileSize::megabytes(1, ByteBase::Decimal)->formatShort(labelStyle: ByteBase::Binary); // "1.00 MB"
```

## Fluent Configuration

### Precision

```php
$size = FileSize::bytes(1234567)->precision(4);

$size->toKilobytes(); // 1205.6318
$size->forHumans();   // "1.1774 Megabytes"
```

### Byte Base

```php
$size = FileSize::megabytes(1)->byteBase(ByteBase::Decimal);

$size->toKilobytes(); // 1000.0
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag="file-size-config"
```

### Available Options

```php
// config/file-size.php
return [
    // Default byte base: 'binary' (1024) or 'decimal' (1000)
    'byte_base' => env('FILE_INFO_BYTE_BASE', 'binary'),

    // Default decimal precision
    'precision' => 2,

    // Formatting options
    'formatting' => [
        'label_style'                  => 'decimal', // 'decimal', 'binary' or null (`null` uses value from `byte_base`)
        'decimal_separator'            => '.',
        'thousands_separator'          => ',',
        'space_between_value_and_unit' => true,
    ],

    // Validation options
    'validation' => [
        'allow_negative_input'     => false,
        'throw_on_negative_result' => false,
    ],
];
```

## Validation

### Negative Values

By default, negative input values throw an exception:

```php
FileSize::megabytes(-5); // Throws NegativeValueException
```

Enable negative values in configuration:

```php
// config/file-size.php
'validation' => [
    'allow_negative_input' => true,
],
```

### Invalid Values

```php
FileSize::bytes(INF); // Throws InvalidValueException
FileSize::bytes(NAN); // Throws InvalidValueException
```

### Division by Zero

```php
$size->divide(0); // Throws InvalidValueException
```

## Accessors

```php
$size = FileSize::megabytes(5, ByteBase::Binary)->precision(4);

$size->getBytes();     // 5242880.0
$size->getByteBase();  // ByteBase::Binary
$size->getPrecision(); // 4
```

## Testing

```bash
composer test
```

## Code Quality

```bash
composer format  # Laravel Pint
composer analyse # PHPStan level 7
composer rector  # Rector suggestions
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
