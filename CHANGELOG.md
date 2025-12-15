# Changelog

All notable changes to `laravel-file-size` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2024-XX-XX

### Added

- Initial release
- `FileSize` class with static factory methods for all units (bytes through petabytes)
- Support for binary (1024-based) and decimal (1000-based) byte systems
- Conversion methods: `toBytes()`, `toKilobytes()`, `toMegabytes()`, `toGigabytes()`, `toTerabytes()`, `toPetabytes()`
- Arithmetic operations: `add()`, `sub()`, `multiply()`, `divide()`, `abs()`
- Convenience arithmetic methods: `addBytes()`, `subKilobytes()`, etc.
- Comparison methods: `equals()`, `notEquals()`, `greaterThan()`, `lessThan()`, `between()`, `min()`, `max()`
- State checks: `isZero()`, `isPositive()`, `isNegative()`
- Formatting methods: `forHumans()`, `format()`, `formatShort()`
- Fluent configuration: `precision()`, `byteBase()`
- Magic property access for unit conversions
- Configurable validation for negative values
- Full configuration file with formatting and validation options
- Comprehensive test suite with 127 tests
- PHPStan level 7 static analysis
- Laravel Pint code styling
- Rector code modernization
