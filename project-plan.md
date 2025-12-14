# Laravel FileSize Package - Complete Technical Plan

## 📦 Package Metadata

- **Package Name**: `martincamen/laravel-file-size`
- **Namespace**: `MartinCamen\FileSize`
- **Description**: Eloquent file size calculations and formatting for Laravel
- **Minimum PHP**: 8.3
- **Minimum Laravel**: 12.x
- **License**: MIT

-----

## 🏗️ Complete Package Structure

```
laravel-file-size/
├── .github/
│   └── workflows/
│       ├── tests.yml           # PHPUnit test runner
│       ├── pint.yml            # Laravel Pint code style
│       ├── larastan.yml        # Static analysis
│       └── rector.yml          # PHP modernization
├── config/
│   └── file-size.php           # Package configuration
├── src/
│   ├── FileSize.php            # Main class
│   ├── Enums/
│   │   ├── Unit.php            # Size units enum
│   │   └── ByteBase.php        # Binary (1024) vs Decimal (1000)
│   ├── Exceptions/
│   │   ├── InvalidUnitException.php
│   │   ├── InvalidValueException.php
│   │   └── NegativeValueException.php
│   ├── Facades/
│   │   └── FileSize.php        # Laravel Facade
│   ├── Concerns/
│   │   ├── HandlesConversions.php
│   │   ├── HandlesArithmetic.php
│   │   ├── HandlesComparisons.php
│   │   └── HandlesFormatting.php
│   └── FileSizeServiceProvider.php
├── tests/
│   ├── Unit/
│   │   ├── ConversionTest.php
│   │   ├── ArithmeticTest.php
│   │   ├── ComparisonTest.php
│   │   ├── FormattingTest.php
│   │   ├── PrecisionTest.php
│   │   ├── ByteBaseTest.php
│   │   └── ValidationTest.php
│   ├── Feature/
│   │   ├── FacadeTest.php
│   │   └── ConfigurationTest.php
│   └── TestCase.php
├── .gitignore
├── .gitattributes
├── composer.json
├── phpunit.xml
├── pint.json
├── phpstan.neon
├── rector.php
├── README.md
├── CHANGELOG.md
├── CONTRIBUTING.md
├── LICENSE.md
└── UPGRADING.md
```

-----

## 🎯 Core Features & Implementation Details

### 1. Configuration File (`config/file-size.php`)

```php
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
    'byte_base' => env('FILE_INFO_BYTE_BASE', \MartinCamen\FileSize\Enums\ByteBase::default()->value),

    /*
    |--------------------------------------------------------------------------
    | Default Precision
    |--------------------------------------------------------------------------
    |
    | Default number of decimal places for conversions.
    | Can be overridden per operation.
    |
    */
    'precision' => 2,

    /*
    |--------------------------------------------------------------------------
    | Formatting Options
    |--------------------------------------------------------------------------
    |
    | Configuration for human-readable output
    |
    */
    'formatting' => [
        'use_binary_notation' => false, // KiB vs KB
        'decimal_separator' => '.',
        'thousands_separator' => ',',
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
        'allow_negative_input' => false,
        'throw_on_negative_result' => false, // After subtraction
    ],
];
```

### 2. ByteBase Enum (`src/Enums/ByteBase.php`)

```php
<?php

namespace MartinCamen\FileSize\Enums;

enum ByteBase: int
{
    case Binary = 'binary';  // IEC standard (KiB, MiB, GiB)
    case Decimal = 'decimal'; // SI standard (KB, MB, GB)

    public static function default(): self
    {
        return self::Decimal;
    }

    public static function fromConfig(): self
    {
        return self::tryFrom(config('file-size.byte_base')) ?? self::default();
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
```

### 3. Unit Enum (`src/Enums/Unit.php`)

```php
<?php

namespace MartinCamen\FileSize\Enums;

enum Unit: int
{
    case Byte = 0;
    case KiloByte = 1;
    case MegaByte = 2;
    case GigaByte = 3;
    case TeraByte = 4;
    case PetaByte = 5;

    public function toBytes(float $value, ByteBase $base = null): float
    {
        return $value * $this->getByteBase($base)->multiply($this->value);
    }

    public function fromBytes(float $bytes, ByteBase $base = null): float
    {
        return $bytes / $this->getByteBase($base)->multiply($this->value);
    }

    public function label(ByteBase $base = null, bool $short = false): string
    {
        if ($this->getByteBase($base) === ByteBase::Binary) {
            return $this->getBinaryLabel($short);
        }

        return $this->getDecimalLabel($short);
    }
    
    public function getBinaryLabel(bool $short = false)
    {
        if ($short) {
            return match ($this) {
                self::Byte => 'B',
                self::KiloByte => 'KiB',
                self::MegaByte => 'MiB',
                self::GigaByte => 'GiB',
                self::TeraByte => 'TiB',
                self::PetaByte => 'PiB',
            };
        }
        
        return match ($this) {
            self::Byte => 'Bytes',
            self::KiloByte => 'Kibibytes',
            self::MegaByte => 'Mebibytes',
            self::GigaByte => 'Gibibytes',
            self::TeraByte => 'Tebibytes',
            self::PetaByte => 'Pebibytes',
        };
    }
    
    public function getDecimalLabel(bool $short = false)
    {
        if ($short) {
            return match ($this) {
                self::Byte => 'B',
                self::KiloByte => 'KB',
                self::MegaByte => 'MB',
                self::GigaByte => 'GB',
                self::TeraByte => 'TB',
                self::PetaByte => 'PB',
            };
        }
        
        return match ($this) {
            self::Byte => 'Bytes',
            self::KiloByte => 'Kilobytes',
            self::MegaByte => 'Megabytes',
            self::GigaByte => 'Gigabytes',
            self::TeraByte => 'Terabytes',
            self::PetaByte => 'Petabytes',
        };
    }

    private function getByteBase(?ByteBase $base = null): ByteBase
    {
        return $base ?? ByteBase::fromConfig();
    }
}
```

### 4. Main FileSize Class (`src/FileSize.php`)

```php
<?php

namespace MartinCamen\FileSize;

use MartinCamen\FileSize\Concerns\HandlesArithmetic;
use MartinCamen\FileSize\Concerns\HandlesComparisons;
use MartinCamen\FileSize\Concerns\HandlesConversions;
use MartinCamen\FileSize\Concerns\HandlesFormatting;
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

    private float $bytes;
    private ByteBase $byteBase;
    private ?int $precision = null;

    private function __construct(float $bytes, ?ByteBase $byteBase = null, ?int $precision = null)
    {
        $this->validateValue($bytes);

        $this->bytes = $bytes;
        $this->byteBase = $byteBase ?? ByteBase::fromConfig();
        $this->precision = $precision;
    }

    public static function bytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        return new self(
            Unit::Byte->toBytes($value, $byteBase),
            $byteBase
        );
    }

    public static function kilobytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        return new self(
            Unit::KiloByte->toBytes($value, $byteBase),
            $byteBase
        );
    }

    public static function megabytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        return new self(
            Unit::MegaByte->toBytes($value, $byteBase),
            $byteBase
        );
    }

    public static function gigabytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        return new self(
            Unit::GigaByte->toBytes($value, $byteBase),
            $byteBase
        );
    }

    public static function terabytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        return new self(
            Unit::TeraByte->toBytes($value, $byteBase),
            $byteBase
        );
    }

    public static function petabytes(int|float $value, ?ByteBase $byteBase = null): self
    {
        return new self(
            Unit::PetaByte->toBytes($value, $byteBase),
            $byteBase
        );
    }

    // Singular forms (default to 1)
    public static function byte(?ByteBase $byteBase = null): self
    {
        return self::bytes(1, $byteBase);
    }

    public static function kilobyte(?ByteBase $byteBase = null): self
    {
        return self::kilobytes(1, $byteBase);
    }

    public static function megabyte(?ByteBase $byteBase = null): self
    {
        return self::megabytes(1, $byteBase);
    }

    public static function gigabyte(?ByteBase $byteBase = null): self
    {
        return self::gigabytes(1, $byteBase);
    }

    public static function terabyte(?ByteBase $byteBase = null): self
    {
        return self::terabytes(1, $byteBase);
    }

    public static function petabyte(?ByteBase $byteBase = null): self
    {
        return self::petabytes(1, $byteBase);
    }

    // Getters via magic method
    public function __get(string $property): float|int
    {
        $unit = $this->propertyToUnit($property);
        $value = Unit::fromBytes($this->bytes, $this->byteBase);

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
            && ! config('file-size.validation.allow_negative_input', false)
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
            'bytes', 'byte' => Unit::Byte,
            'kilobyte', 'kb' => Unit::KiloByte,
            'megabyte', 'mb' => Unit::MegaByte,
            'gigabyte', 'gb' => Unit::GigaByte,
            'terabyte', 'tb' => Unit::TeraByte,
            'petabyte', 'pb' => Unit::PetaByte,
            default => throw new InvalidValueException("Unknown property: {$property}"),
        };
    }

    // Accessors for internal state
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
```

### 5. Concerns/Traits

#### HandlesConversions (`src/Concerns/HandlesConversions.php`)

```php
<?php

namespace MartinCamen\FileSize\Concerns;

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
        $precision ??= $this->precision ?? config('file-size.precision', 2);

        return round($value, $precision);
    }
}
```

#### HandlesArithmetic (`src/Concerns/HandlesArithmetic.php`)

```php
<?php

namespace MartinCamen\FileSize\Concerns;

use MartinCamen\FileSize\Enums\Unit;
use MartinCamen\FileSize\FileSize;

trait HandlesArithmetic
{
    public function add(int|float $value, Unit $unit): self
    {
        $bytes = $this->bytes + $unit->toBytes($value, $this->byteBase);

        return new FileSize($bytes, $this->byteBase, $this->precision);
    }

    public function sub(int|float $value, Unit $unit): self
    {
        $bytes = $this->bytes - $unit->toBytes($value, $this->byteBase);
        
        if ($bytes < 0 && config('file-size.validation.throw_on_negative_result', false)) {
            throw new NegativeValueException('Subtraction resulted in negative value.');
        }

        return new FileSize($bytes, $this->byteBase, $this->precision);
    }

    public function multiply(int|float $multiplier): self
    {
        $bytes = $this->bytes * $multiplier;

        return new FileSize($bytes, $this->byteBase, $this->precision);
    }

    public function divide(int|float $divisor): self
    {
        if ($divisor == 0) {
            throw new InvalidValueException('Cannot divide by zero.');
        }

        $bytes = $this->bytes / $divisor;

        return new FileSize($bytes, $this->byteBase, $this->precision);
    }

    // Convenience methods for each unit
    public function addBytes(int|float $value): self
    {
        return $this->add($value, Unit::Byte);
    }

    public function subBytes(int|float $value): self
    {
        return $this->sub($value, Unit::Byte);
    }

    public function addKilobytes(int|float $value): self
    {
        return $this->add($value, Unit::KiloByte);
    }

    public function subKilobytes(int|float $value): self
    {
        return $this->sub($value, Unit::KiloByte);
    }

    public function addMegabytes(int|float $value): self
    {
        return $this->add($value, Unit::MegaByte);
    }

    public function subMegabytes(int|float $value): self
    {
        return $this->sub($value, Unit::MegaByte);
    }

    public function addGigabytes(int|float $value): self
    {
        return $this->add($value, Unit::GigaByte);
    }

    public function subGigabytes(int|float $value): self
    {
        return $this->sub($value, Unit::GigaByte);
    }

    public function addTerabytes(int|float $value): self
    {
        return $this->add($value, Unit::TeraByte);
    }

    public function subTerabytes(int|float $value): self
    {
        return $this->sub($value, Unit::TeraByte);
    }

    public function addPetabytes(int|float $value): self
    {
        return $this->add($value, Unit::PetaByte);
    }

    public function subPetabytes(int|float $value): self
    {
        return $this->sub($value, Unit::PetaByte);
    }

    // Absolute value
    public function abs(): self
    {
        return new FileSize(abs($this->bytes), $this->byteBase, $this->precision);
    }
}
```

#### HandlesComparisons (`src/Concerns/HandlesComparisons.php`)

```php
<?php

namespace MartinCamen\FileSize\Concerns;

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
        return !$this->equals($value, $unit, $precision);
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
        $precision ??= $this->precision ?? config('file-size.precision', 2);
        
        $thisValue = round($this->bytes, $precision);
        $compareValue = round($unit->toBytes($value, $this->byteBase), $precision);

        return $thisValue <=> $compareValue;
    }
}
```

#### HandlesFormatting (`src/Concerns/HandlesFormatting.php`)

```php
<?php

namespace MartinCamen\FileSize\Concerns;

use MartinCamen\FileSize\Enums\Unit;

trait HandlesFormatting
{
    public function forHumans(bool $short = false, ?int $precision = null): string
    {
        $precision ??= $this->precision ?? config('file-size.precision', 2);
        
        $unit = $this->bestUnit();
        $value = $unit->fromBytes($this->bytes, $this->byteBase);
        
        $formattedValue = number_format(
            round($value, $precision),
            $precision,
            config('file-size.formatting.decimal_separator', '.'),
            config('file-size.formatting.thousands_separator', ',')
        );

        $label = $unit->label($this->byteBase, $short);
        $space = config('file-size.formatting.space_between_value_and_unit', true) ? ' ' : '';

        return "{$formattedValue}{$space}{$label}";
    }

    public function format(?int $precision = null): string
    {
        return $this->forHumans(false, $precision);
    }

    public function formatShort(?int $precision = null): string
    {
        return $this->forHumans(true, $precision);
    }

    private function bestUnit(): Unit
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
```

### 6. Facade (`src/Facades/FileSize.php`)

```php
<?php

namespace MartinCamen\FileSize\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \MartinCamen\FileSize\FileSize bytes(int|float $value, ?\MartinCamen\FileSize\Enums\ByteBase $byteBase = null)
 * @method static \MartinCamen\FileSize\FileSize kilobytes(int|float $value, ?\MartinCamen\FileSize\Enums\ByteBase $byteBase = null)
 * @method static \MartinCamen\FileSize\FileSize megabytes(int|float $value, ?\MartinCamen\FileSize\Enums\ByteBase $byteBase = null)
 * @method static \MartinCamen\FileSize\FileSize gigabytes(int|float $value, ?\MartinCamen\FileSize\Enums\ByteBase $byteBase = null)
 * @method static \MartinCamen\FileSize\FileSize terabytes(int|float $value, ?\MartinCamen\FileSize\Enums\ByteBase $byteBase = null)
 * @method static \MartinCamen\FileSize\FileSize petabytes(int|float $value, ?\MartinCamen\FileSize\Enums\ByteBase $byteBase = null)
 * @method static \MartinCamen\FileSize\FileSize byte(?\MartinCamen\FileSize\Enums\ByteBase $byteBase = null)
 * @method static \MartinCamen\FileSize\FileSize kilobyte(?\MartinCamen\FileSize\Enums\ByteBase $byteBase = null)
 * @method static \MartinCamen\FileSize\FileSize megabyte(?\MartinCamen\FileSize\Enums\ByteBase $byteBase = null)
 * @method static \MartinCamen\FileSize\FileSize gigabyte(?\MartinCamen\FileSize\Enums\ByteBase $byteBase = null)
 * @method static \MartinCamen\FileSize\FileSize terabyte(?\MartinCamen\FileSize\Enums\ByteBase $byteBase = null)
 * @method static \MartinCamen\FileSize\FileSize petabyte(?\MartinCamen\FileSize\Enums\ByteBase $byteBase = null)
 *
 * @see \MartinCamen\FileSize\FileSize
 */
class FileSize extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \MartinCamen\FileSize\FileSize::class;
    }
}
```

### 7. Service Provider (`src/FileSizeServiceProvider.php`)

```php
<?php

namespace MartinCamen\FileSize;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FileSizeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-file-size')
            ->hasConfigFile('file-size');
    }
}
```

-----

## 📝 Composer Configuration

```json
{
    "name": "martincamen/laravel-file-size",
    "description": "Eloquent file size calculations and formatting for Laravel",
    "keywords": [
        "laravel",
        "file-size",
        "formatting",
        "bytes",
        "storage"
    ],
    "homepage": "https://github.com/martincamen/laravel-file-size",
    "license": "MIT",
    "authors": [
        {
            "name": "Martin Camen",
            "role": "Developer"
        }
    ],
    "require": {
        "php": "^8.3",
        "illuminate/support": "^12.0",
        "spatie/laravel-package-tools": "^1.16"
    },
    "require-dev": {
        "laravel/pint": "^1.17",
        "larastan/larastan": "^3.0",
        "orchestra/testbench": "^10.0",
        "pestphp/pest": "^3.0",
        "pestphp/pest-plugin-laravel": "^3.0",
        "phpstan/phpstan": "^2.0",
        "rector/rector": "^2.0"
    },
    "autoload": {
        "psr-4": {
            "MartinCamen\\FileSize\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "MartinCamen\\FileSize\\Tests\\": "tests/"
        }
    },
    "scripts": {
        "test": "vendor/bin/pest",
        "test-coverage": "vendor/bin/pest --coverage",
        "format": "vendor/bin/pint",
        "analyse": "vendor/bin/phpstan analyse",
        "rector": "vendor/bin/rector process --dry-run",
        "rector-fix": "vendor/bin/rector process"
    },
    "config": {
        "sort-packages": true,
        "allow-plugins": {
            "pestphp/pest-plugin": true
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "MartinCamen\\FileSize\\FileSizeServiceProvider"
            ],
            "aliases": {
                "FileSize": "MartinCamen\\FileSize\\Facades\\FileSize"
            }
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

-----

## 🧪 Testing Strategy

### Test Categories

1. **Unit Tests**

- Conversion accuracy (all unit combinations)
- Arithmetic operations (add, sub, multiply, divide)
- Comparison methods (equals, greaterThan, lessThan, etc.)
- Precision handling (various precision levels)
- ByteBase switching (binary vs decimal)
- Edge cases (zero, negative results, overflow)

1. **Feature Tests**

- Facade functionality
- Configuration loading
- Service provider registration

1. **Test Coverage Requirements**

- Minimum 95% code coverage
- All public methods must have tests
- All edge cases must be covered

### Example Test (`tests/Unit/ConversionTest.php`)

```php
<?php

use MartinCamen\FileSize\Facades\FileSize;
use MartinCamen\FileSize\Enums\ByteBase;

it('converts megabytes to kilobytes', function () {
    expect(FileSize::megabytes(2)->kilobytes)
        ->toBe(2048.0);
});

it('converts kilobytes to gigabytes with precision', function () {
    expect(FileSize::kilobytes(2048)->precision(6)->gigabytes)
        ->toBe(0.002048);
});

it('handles singular forms', function () {
    expect(FileSize::megabyte()->kilobytes)
        ->toBe(1024.0);
});

it('chains arithmetic operations', function () {
    $result = FileSize::megabytes(2)
        ->subKilobytes(22)
        ->addKilobytes(8)
        ->kilobytes;
    
    expect($result)->toBe(2034.0);
});

it('supports decimal byte base', function () {
    $result = FileSize::megabytes(2, ByteBase::Decimal)
        ->kilobytes;
    
    expect($result)->toBe(2000.0);
});

it('formats for humans', function () {
    expect(FileSize::megabytes(1.5)->forHumans())
        ->toBe('1.50 MiB');
});
```

-----

## 🔧 Quality Tools Configuration

### Laravel Pint (`pint.json`)

```json
{
  "preset": "laravel",
  "rules": {
    "simplified_null_return": false,
    "php_unit_attributes": true,
    "explicit_string_variable": true,
    "logical_operators": true,
    "fully_qualified_strict_types": true,
    "no_useless_else": true,
    "single_line_empty_body": true,
    "trailing_comma_in_multiline": {
      "elements": ["arguments", "arrays", "match", "parameters"]
    },
    "new_with_parentheses": {
      "named_class": true,
      "anonymous_class": false
    },
    "method_argument_space": {
      "on_multiline": "ensure_fully_multiline",
      "keep_multiple_spaces_after_comma": false
    },
    "concat_space": {
      "spacing": "one"
    },
    "binary_operator_spaces": {
      "operators": {
        "=>": "align"
      }
    },
    "function_declaration": {
      "closure_fn_spacing": "none"
    },
    "php_unit_method_casing": {
      "case": "camel_case"
    },
    "phpdoc_align": {
      "align": "left",
      "spacing": {
        "param": 1
      }
    },
    "class_attributes_separation": {
      "elements": {
        "const": "none",
        "method": "one",
        "property": "none",
        "trait_import": "none"
      }
    }
  }
}
```

### Larastan (`phpstan.neon`)

```neon
includes:
    - vendor/larastan/larastan/extension.neon

parameters:
    paths:
        - src
    level: 7
    checkMissingIterableValueType: false
    checkGenericClassInNonGenericObjectType: false
    reportUnmatchedIgnoredErrors: false
```

### Rector (`rector.php`)

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSets([
        LevelSetList::UP_TO_PHP_83,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
        LaravelSetList::LARAVEL_110,
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        earlyReturn: true
    );
```

### PHPUnit (`phpunit.xml`)

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
         cacheDirectory=".phpunit.cache"
         requireCoverageMetadata="true"
         beStrictAboutCoverageMetadata="true"
         beStrictAboutOutputDuringTests="true"
         failOnRisky="true"
         failOnWarning="true">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>src</directory>
        </include>
    </source>
    <coverage>
        <report>
            <html outputDirectory="coverage-report"/>
            <text outputFile="php://stdout" showOnlySummary="true"/>
        </report>
    </coverage>
</phpunit>
```

-----

## 🚀 GitHub Actions CI/CD

### Tests Workflow (`.github/workflows/tests.yml`)

```yaml
name: Tests

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main, develop ]

jobs:
  test:
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php: [ 8.3 ]
        laravel: [ 12.* ]
        dependency-version: [ prefer-stable ]

    name: PHP ${{ matrix.php }} - Laravel ${{ matrix.laravel }} - ${{ matrix.dependency-version }}

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}
          extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite
          coverage: xdebug

      - name: Install dependencies
        run: |
          composer require "illuminate/support:${{ matrix.laravel }}" --no-interaction --no-update
          composer update --${{ matrix.dependency-version }} --prefer-dist --no-interaction

      - name: Execute tests
        run: vendor/bin/pest --coverage --min=95

  code-style:
    runs-on: ubuntu-latest
    name: Code Style

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.3

      - name: Install dependencies
        run: composer install --prefer-dist --no-interaction

      - name: Run Pint
        run: vendor/bin/pint --test

  static-analysis:
    runs-on: ubuntu-latest
    name: Static Analysis

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.3

      - name: Install dependencies
        run: composer install --prefer-dist --no-interaction

      - name: Run Larastan
        run: vendor/bin/phpstan analyse

  rector:
    runs-on: ubuntu-latest
    name: Rector

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.3

      - name: Install dependencies
        run: composer install --prefer-dist --no-interaction

      - name: Run Rector
        run: vendor/bin/rector process --dry-run
```

-----

## 📚 Usage Examples

### Basic Conversions

```php
use MartinCamen\FileSize\Facades\FileSize;

// Convert between units
FileSize::megabytes(2)->kilobytes; // 2048.0
FileSize::kilobytes(2048)->gigabytes; // 0.00 (default precision: 2)
FileSize::kilobytes(2048)->precision(6)->gigabytes; // 0.002048

// Singular forms
FileSize::megabyte()->kilobytes; // 1024.0
FileSize::gigabyte()->megabytes; // 1024.0
```

### Arithmetic Operations

```php
// Chaining operations
FileSize::megabytes(2)
    ->subKilobytes(22)
    ->addKilobytes(8)
    ->kilobytes; // 2034.0

// Multiply and divide
FileSize::megabytes(5)->multiply(2)->megabytes; // 10.0
FileSize::gigabytes(10)->divide(2)->gigabytes; // 5.0

// Absolute value
FileSize::megabytes(-5)->abs()->megabytes; // 5.0
```

### Comparisons

```php
use MartinCamen\FileSize\Enums\Unit;

// Basic comparisons
FileSize::megabytes(2)->equals(2048, Unit::KiloByte); // true
FileSize::gigabytes(1)->greaterThan(500, Unit::MegaByte); // true
FileSize::kilobytes(500)->lessThan(1, Unit::MegaByte); // true

// With precision
FileSize::kilobytes(1024)->equals(1, Unit::MegaByte, precision: 0); // true

// Range checks
FileSize::megabytes(512)->between(100, 1000, Unit::MegaByte); // true

// State checks
FileSize::bytes(0)->isZero(); // true
FileSize::megabytes(5)->isPositive(); // true
```

### Formatting

```php
// Human-readable output
FileSize::megabytes(1.5)->forHumans(); // "1.50 MiB"
FileSize::gigabytes(2.5)->formatShort(); // "2.50 GiB"

// Custom precision
FileSize::kilobytes(1536)->precision(1)->forHumans(); // "1.5 MiB"

// Decimal byte base
use MartinCamen\FileSize\Enums\ByteBase;

FileSize::megabytes(2, ByteBase::Decimal)->forHumans(); // "2.00 MB"
```

### Configuration

```php
// Per-operation byte base
FileSize::megabytes(2)
    ->byteBase(ByteBase::Decimal)
    ->kilobytes; // 2000.0

// Per-operation precision
FileSize::kilobytes(2048)
    ->precision(6)
    ->gigabytes; // 0.002048
```

-----

## ✅ Complete Todo List

### Phase 1: Project Setup

- [ ] Set up directory structure
- [ ] Initialize composer.json with dependencies
- [ ] Configure Laravel Pint (pint.json)
- [ ] Configure Larastan (phpstan.neon)
- [ ] Configure Rector (rector.php)
- [ ] Configure PHPUnit (phpunit.xml)
- [ ] Set up GitHub Actions workflows
- [ ] Create .gitignore and .gitattributes
- [ ] Add MIT license file

### Phase 2: Core Development

- [ ] Create ByteBase enum
- [ ] Create Unit enum with conversion methods
- [ ] Create custom exceptions
    - [ ] InvalidUnitException
    - [ ] InvalidValueException
    - [ ] NegativeValueException
- [ ] Implement main FileSize class
    - [ ] Constructor and static factories
    - [ ] Magic __get for property access
    - [ ] Precision and ByteBase fluent setters
- [ ] Create HandlesConversions trait
    - [ ] toBytes(), toKilobytes(), toMegabytes(), etc.
- [ ] Create HandlesArithmetic trait
    - [ ] add(), sub(), multiply(), divide()
    - [ ] Convenience methods (addKilobytes, subMegabytes, etc.)
    - [ ] abs() method
- [ ] Create HandlesComparisons trait
    - [ ] equals(), notEquals()
    - [ ] greaterThan(), greaterThanOrEqual()
    - [ ] lessThan(), lessThanOrEqual()
    - [ ] between(), min(), max()
    - [ ] isZero(), isPositive(), isNegative()
- [ ] Create HandlesFormatting trait
    - [ ] forHumans() with short option
    - [ ] format() and formatShort() aliases
    - [ ] bestUnit() helper

### Phase 3: Laravel Integration

- [ ] Create configuration file (config/file-size.php)
- [ ] Create FileSizeServiceProvider using Spatie package tools
- [ ] Create FileSize Facade with proper docblocks
- [ ] Test service provider auto-discovery

### Phase 4: Testing (Pest)

- [ ] Set up base TestCase
- [ ] Unit Tests - Conversions
    - [ ] Test all unit-to-unit conversions
    - [ ] Test singular forms
    - [ ] Test precision handling
    - [ ] Test ByteBase switching
- [ ] Unit Tests - Arithmetic
    - [ ] Test add/sub operations
    - [ ] Test multiply/divide operations
    - [ ] Test method chaining
    - [ ] Test negative result handling
    - [ ] Test abs() method
- [ ] Unit Tests - Comparisons
    - [ ] Test all comparison methods
    - [ ] Test precision in comparisons
    - [ ] Test edge cases (zero, negative)
- [ ] Unit Tests - Formatting
    - [ ] Test forHumans() output
    - [ ] Test bestUnit() selection
    - [ ] Test configuration options
- [ ] Unit Tests - Validation
    - [ ] Test negative input validation
    - [ ] Test invalid values
    - [ ] Test divide by zero
- [ ] Feature Tests
    - [ ] Test Facade functionality
    - [ ] Test configuration loading
    - [ ] Test service provider
- [ ] Achieve 95%+ code coverage

### Phase 5: Quality Assurance

- [ ] Run Laravel Pint and fix all issues
- [ ] Run Larastan at level 7 and fix all issues
- [ ] Run Rector and apply suggestions
- [ ] Review all PHPDoc blocks
- [ ] Verify all public methods have return types
- [ ] Check code follows PSR-12 standards

### Phase 6: Documentation

- [ ] Write comprehensive README.md
    - [ ] Installation instructions
    - [ ] Quick start guide
    - [ ] All usage examples
    - [ ] Configuration options
    - [ ] API reference
- [ ] Create CHANGELOG.md
- [ ] Create CONTRIBUTING.md
- [ ] Create UPGRADING.md (for future versions)
- [ ] Add inline code documentation
- [ ] Document edge cases and limitations

### Phase 7: Final review

- [ ] Review all files one final time
- [ ] Verify composer.json metadata
- [ ] Create a separate markdown file with release notes for GitHub

~~### Phase 8: Publishing (Will be done later)

- [ ] Tag version 1.0.0
- [ ] Push to GitHub
- [ ] Submit to Packagist~~

~~### Phase 9: Post-Launch (Will be done later)

- [ ] Monitor for issues
- [ ] Respond to community feedback
- [ ] Plan for future features
- [ ] Set up documentation website (optional)~~

-----

## 📊 Comparison with Carbon (Negative Value Handling)

Based on Carbon’s approach:

- Carbon allows negative values for date arithmetic (e.g., `subDays(-5)` adds 5 days)
- Carbon’s `abs()` method returns absolute values
- Carbon doesn’t throw on negative results from subtraction

**Recommended approach for FileSize:**

- Allow negative values in results (don’t throw by default)
- Provide config option to throw on negative results if desired
- Provide `abs()` method for getting absolute values
- Validation primarily prevents negative *input* to constructors

This aligns with Carbon’s philosophy of flexibility while maintaining correctness.

-----

## 🎯 Key Design Decisions

1. **Immutability**: All operations return new instances (like Laravel’s Stringable)
2. **Fluent Interface**: Method chaining for readability
3. **Configuration Flexibility**: Per-operation precision and byte base
4. **Laravel-like API**: Mirrors Number and Date class patterns
5. **Strict Typing**: PHP 8.3 features with proper type hints
6. **Comprehensive Testing**: High coverage with Pest
7. **Code Quality**: Multiple tools ensuring maintainability

-----

## 🚀 Next Steps

Ready to start implementation! The plan covers:

- ✅ Complete package structure
- ✅ All core features with detailed implementations
- ✅ Comprehensive testing strategy
- ✅ Quality assurance tools configured
- ✅ CI/CD pipeline
- ✅ Documentation plan
