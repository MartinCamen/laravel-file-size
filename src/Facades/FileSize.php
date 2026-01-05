<?php

namespace MartinCamen\FileSize\Facades;

use Illuminate\Support\Facades\Facade;
use MartinCamen\FileSize\Services\FileSizeService;
use MartinCamen\PhpFileSize\Enums\ByteBase;
use MartinCamen\PhpFileSize\Enums\Unit;
use MartinCamen\PhpFileSize\FileSize as PhpFileSize;
use SplFileInfo;

/**
 * @method static FileSizeService bytes(int|float $value, array $options = [])
 * @method static FileSizeService kilobytes(int|float $value, array $options = [])
 * @method static FileSizeService megabytes(int|float $value, array $options = [])
 * @method static FileSizeService gigabytes(int|float $value, array $options = [])
 * @method static FileSizeService terabytes(int|float $value, array $options = [])
 * @method static FileSizeService petabytes(int|float $value, array $options = [])
 * @method static FileSizeService byte(array $options = [])
 * @method static FileSizeService kilobyte(array $options = [])
 * @method static FileSizeService megabyte(array $options = [])
 * @method static FileSizeService gigabyte(array $options = [])
 * @method static FileSizeService terabyte(array $options = [])
 * @method static FileSizeService petabyte(array $options = [])
 * @method static FileSizeService fromFile(string|SplFileInfo $file, array $options = [])
 * @method static FileSizeService precision(int $precision)
 * @method static FileSizeService getPrecision()
 * @method static FileSizeService byteBase(ByteBase $byteBase)
 * @method static FileSizeService getByteBase()
 * @method static FileSizeService getBytes()
 * @method static FileSizeService evaluate()
 * @method static FileSizeService add(int|float $value, Unit $unit)
 * @method static FileSizeService sub(int|float $value, Unit $unit)
 * @method static FileSizeService multiply(int|float $value)
 * @method static FileSizeService divide(int|float $value)
 * @method static FileSizeService addBytes(int|float $value)
 * @method static FileSizeService subBytes(int|float $value)
 * @method static FileSizeService addKilobytes(int|float $value)
 * @method static FileSizeService subKilobytes(int|float $value)
 * @method static FileSizeService addMegabytes(int|float $value)
 * @method static FileSizeService subMegabytes(int|float $value)
 * @method static FileSizeService addGigabytes(int|float $value)
 * @method static FileSizeService subGigabytes(int|float $value)
 * @method static FileSizeService addTerabytes(int|float $value)
 * @method static FileSizeService subTerabytes(int|float $value)
 * @method static FileSizeService addPetabytes(int|float $value)
 * @method static FileSizeService subPetabytes(int|float $value)
 * @method static FileSizeService abs()
 * @method static FileSizeService equals(int|float $value, Unit $unit, array $options = [])
 * @method static FileSizeService notEquals(int|float $value, Unit $unit, array $options = [])
 * @method static FileSizeService greaterThanOrEqual(int|float $value, Unit $unit, array $options = [])
 * @method static FileSizeService lessThan(int|float $value, Unit $unit, array $options = [])
 * @method static FileSizeService lessThanOrEqual(int|float $value, Unit $unit, array $options = [])
 * @method static FileSizeService between(int|float $min, int|float $max, Unit $unit, array $options = [])
 * @method static FileSizeService min(PhpFileSize $fileSize)
 * @method static FileSizeService max(PhpFileSize $fileSize)
 * @method static FileSizeService isZero(array $options = [])
 * @method static FileSizeService isPositive()
 * @method static FileSizeService isNegative()
 * @method static FileSizeService toBytes(?int $precision = null)
 * @method static FileSizeService toKilobytes(?int $precision = null)
 * @method static FileSizeService toMegabytes(?int $precision = null)
 * @method static FileSizeService toGigabytes(?int $precision = null)
 * @method static FileSizeService toTerabytes(?int $precision = null)
 * @method static FileSizeService toPetabytes(?int $precision = null)
 * @method static FileSizeService forHumans(bool $short = false, array $options = [])
 * @method static FileSizeService format(array $options = [])
 * @method static FileSizeService formatShort(array $options = [])
 * @method static FileSizeService inBinaryFormat()
 * @method static FileSizeService inDecimalFormat()
 * @method static FileSizeService withTypeBaseFormat(ByteBase $byteBase)
 * @method static FileSizeService withBinaryLabel()
 * @method static FileSizeService withLabelStyle(ByteBase $byteBase)
 */
class FileSize extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FileSizeService::class;
    }
}
