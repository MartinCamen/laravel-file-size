<?php

namespace MartinCamen\FileSize\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \MartinCamen\PhpFileSize\FileSize bytes(int|float $value, array $options = [])
 * @method static \MartinCamen\PhpFileSize\FileSize kilobytes(int|float $value, array $options = [])
 * @method static \MartinCamen\PhpFileSize\FileSize megabytes(int|float $value, array $options = [])
 * @method static \MartinCamen\PhpFileSize\FileSize gigabytes(int|float $value, array $options = [])
 * @method static \MartinCamen\PhpFileSize\FileSize terabytes(int|float $value, array $options = [])
 * @method static \MartinCamen\PhpFileSize\FileSize petabytes(int|float $value, array $options = [])
 * @method static \MartinCamen\PhpFileSize\FileSize byte(array $options = [])
 * @method static \MartinCamen\PhpFileSize\FileSize kilobyte(array $options = [])
 * @method static \MartinCamen\PhpFileSize\FileSize megabyte(array $options = [])
 * @method static \MartinCamen\PhpFileSize\FileSize gigabyte(array $options = [])
 * @method static \MartinCamen\PhpFileSize\FileSize terabyte(array $options = [])
 * @method static \MartinCamen\PhpFileSize\FileSize petabyte(array $options = [])
 */
class FileSize extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \MartinCamen\PhpFileSize\FileSize::class;
    }
}
