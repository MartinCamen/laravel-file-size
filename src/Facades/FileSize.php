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
        return 'file-size';
    }
}
