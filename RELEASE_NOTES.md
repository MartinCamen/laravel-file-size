# Release Notes - v1.0.0

## Overview

Laravel FileSize is a fluent, immutable API for converting, comparing, and formatting file sizes in Laravel with support for both binary (1024-based) and decimal (1000-based) byte systems.

## Features

### File Size Creation
- Static factory methods for all units: `bytes()`, `kilobytes()`, `megabytes()`, `gigabytes()`, `terabytes()`, `petabytes()`
- Singular convenience methods: `byte()`, `kilobyte()`, `megabyte()`, `gigabyte()`, `terabyte()`, `petabyte()`
- Support for binary (IEC) and decimal (SI) byte bases

### Conversions
- Convert between any units with configurable precision
- Methods: `toBytes()`, `toKilobytes()`, `toMegabytes()`, `toGigabytes()`, `toTerabytes()`, `toPetabytes()`
- Magic property access: `$size->kilobytes`, `$size->megabytes`, etc.

### Arithmetic Operations
- Add and subtract with any unit: `addMegabytes()`, `subGigabytes()`, etc.
- Generic operations: `add()`, `sub()` with `Unit` enum
- Multiplication and division: `multiply()`, `divide()`
- Absolute value: `abs()`
- Full method chaining support

### Comparisons
- Equality: `equals()`, `notEquals()`
- Ordering: `greaterThan()`, `greaterThanOrEqual()`, `lessThan()`, `lessThanOrEqual()`
- Range: `between()`
- Min/Max: `min()`, `max()`
- State: `isZero()`, `isPositive()`, `isNegative()`

### Formatting
- Human-readable output: `forHumans()`, `format()`, `formatShort()`
- Automatic best unit selection
- Binary labels (KiB, MiB, GiB) and decimal labels (KB, MB, GB)
- Configurable separators and spacing

### Configuration
- Default byte base (binary/decimal)
- Default precision
- Formatting options (separators, spacing)
- Validation options (negative values)

## Requirements

- PHP 8.3+
- Laravel 12.x+

## Installation

```bash
composer require martincamen/laravel-file-size
```

## Quick Example

```php
use MartinCamen\FileSize\FileSize;

// Create a file size
$size = FileSize::megabytes(5);

// Convert
$size->toKilobytes();  // 5120.0

// Format
$size->forHumans();    // "5.00 Mebibytes"

// Compare
$size->greaterThan(4, Unit::MegaByte);  // true

// Calculate
$size->addMegabytes(10)->divide(3)->toMegabytes();  // 5.0
```

## Quality

- 127 passing tests
- PHPStan level 7 with no errors
- Laravel Pint code style
- Rector code modernization applied

## License

MIT License
