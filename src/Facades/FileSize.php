<?php

namespace MartinCamen\FileSize\Facades;

use Illuminate\Support\Facades\Facade;
use MartinCamen\FileSize\Services\FileSizeService;
use MartinCamen\PhpFileSize\Configuration\FileSizeOptions;
use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\Unit;
use MartinCamen\PhpFileSize\FileSize as PhpFileSize;
use SplFileInfo;

/**
 * @phpstan-import-type OptionalFileSizeOptionsType from FileSizeOptions
 *
 * @method static PhpFileSize bytes(int|float $value, OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize kilobytes(int|float $value, OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize megabytes(int|float $value, OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize gigabytes(int|float $value, OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize terabytes(int|float $value, OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize petabytes(int|float $value, OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize byte(OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize kilobyte(OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize megabyte(OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize gigabyte(OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize terabyte(OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize petabyte(OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize fromFile(string|SplFileInfo $file, OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize precision(int $precision)
 * @method static int getPrecision()
 * @method static PhpFileSize byteBase(ByteBase $byteBase)
 * @method static ByteBase getByteBase()
 * @method static float getBytes()
 * @method static float evaluate()
 * @method static PhpFileSize add(int|float $value, Unit $unit)
 * @method static PhpFileSize sub(int|float $value, Unit $unit)
 * @method static PhpFileSize multiply(int|float $value)
 * @method static PhpFileSize divide(int|float $value)
 * @method static PhpFileSize addBytes(int|float $value)
 * @method static PhpFileSize subBytes(int|float $value)
 * @method static PhpFileSize addKilobytes(int|float $value)
 * @method static PhpFileSize subKilobytes(int|float $value)
 * @method static PhpFileSize addMegabytes(int|float $value)
 * @method static PhpFileSize subMegabytes(int|float $value)
 * @method static PhpFileSize addGigabytes(int|float $value)
 * @method static PhpFileSize subGigabytes(int|float $value)
 * @method static PhpFileSize addTerabytes(int|float $value)
 * @method static PhpFileSize subTerabytes(int|float $value)
 * @method static PhpFileSize addPetabytes(int|float $value)
 * @method static PhpFileSize subPetabytes(int|float $value)
 * @method static PhpFileSize abs()
 * @method static bool equals(int|float $value, Unit $unit, OptionalFileSizeOptionsType $options = [])
 * @method static bool notEquals(int|float $value, Unit $unit, OptionalFileSizeOptionsType $options = [])
 * @method static bool greaterThanOrEqual(int|float $value, Unit $unit, OptionalFileSizeOptionsType $options = [])
 * @method static bool lessThan(int|float $value, Unit $unit, OptionalFileSizeOptionsType $options = [])
 * @method static bool lessThanOrEqual(int|float $value, Unit $unit, OptionalFileSizeOptionsType $options = [])
 * @method static bool between(int|float $min, int|float $max, Unit $unit, OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize min(PhpFileSize $fileSize)
 * @method static PhpFileSize max(PhpFileSize $fileSize)
 * @method static bool isZero(OptionalFileSizeOptionsType $options = [])
 * @method static bool isPositive()
 * @method static bool isNegative()
 * @method static float toBytes(?int $precision = null)
 * @method static float toKilobytes(?int $precision = null)
 * @method static float toMegabytes(?int $precision = null)
 * @method static float toGigabytes(?int $precision = null)
 * @method static float toTerabytes(?int $precision = null)
 * @method static float toPetabytes(?int $precision = null)
 * @method static string forHumans(bool $short = false, OptionalFileSizeOptionsType $options = [])
 * @method static string format(OptionalFileSizeOptionsType $options = [])
 * @method static string formatShort(OptionalFileSizeOptionsType $options = [])
 * @method static PhpFileSize inBinaryFormat()
 * @method static PhpFileSize inDecimalFormat()
 * @method static PhpFileSize withTypeBaseFormat(ByteBase $byteBase)
 * @method static PhpFileSize withBinaryLabel()
 * @method static PhpFileSize withLabelStyle(ByteBase $byteBase)
 */
class FileSize extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FileSizeService::class;
    }
}
