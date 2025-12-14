# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel package for file size calculations and formatting (`martincamen/laravel-file-size`). Provides a fluent, immutable API for converting, comparing, and formatting file sizes with support for both binary (1024-based) and decimal (1000-based) byte systems.

- **Namespace**: `MartinCamen\FileSize`
- **Minimum PHP**: 8.3
- **Minimum Laravel**: 12.x

## Common Commands

```bash
# Install dependencies
composer install

# Run tests
composer test                    # or: vendor/bin/pest

# Run tests with coverage (minimum 95% required)
composer test-coverage           # or: vendor/bin/pest --coverage

# Code formatting
composer format                  # or: vendor/bin/pint

# Static analysis (level 7)
composer analyse                 # or: vendor/bin/phpstan analyse

# Code modernization check
composer rector                  # or: vendor/bin/rector process --dry-run
composer rector-fix              # Apply rector fixes
```

## Architecture

### Core Design Principles

- **Immutability**: All operations return new instances (never mutate)
- **Fluent Interface**: Method chaining for readability
- **Per-operation configuration**: Precision and byte base can be set per operation

### Key Components

- `src/FileSize.php` - Main class with static factory methods (`bytes()`, `kilobytes()`, etc.) and private constructor
- `src/Enums/Unit.php` - Enum for size units (Byte through PetaByte) with conversion logic
- `src/Enums/ByteBase.php` - Enum for binary (1024) vs decimal (1000) base system
- `src/Concerns/` - Traits split by functionality:
  - `HandlesConversions` - `toBytes()`, `toKilobytes()`, etc.
  - `HandlesArithmetic` - `add()`, `sub()`, `multiply()`, `divide()`, `abs()`
  - `HandlesComparisons` - `equals()`, `greaterThan()`, `lessThan()`, `between()`, etc.
  - `HandlesFormatting` - `forHumans()`, `format()`, `formatShort()`

### Service Provider

Uses `spatie/laravel-package-tools` for service provider implementation. Config is published to `config/file-size.php`.

### Testing

Uses Pest for testing. Tests are organized into:
- `tests/Unit/` - Conversion, arithmetic, comparison, formatting, precision, byte base, validation tests
- `tests/Feature/` - Facade and configuration tests
- `tests/TestCase.php` - Base test case using Orchestra Testbench

## Code Style

- Laravel Pint preset with custom rules (see `pint.json`)
- Larastan level 7 for static analysis
- All public methods must have return types and PHPDoc blocks
- Use PHP 8.3 features (enums, constructor property promotion, match expressions)
