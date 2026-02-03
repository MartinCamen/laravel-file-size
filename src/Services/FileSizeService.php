<?php

namespace MartinCamen\FileSize\Services;

use BadMethodCallException;
use Illuminate\Http\UploadedFile;
use MartinCamen\FileSize\Configuration\FileSizeConfigurationOptions;
use MartinCamen\PhpFileSize\Configuration\FileSizeOptions;
use MartinCamen\PhpFileSize\FileSize;
use SplFileInfo;

/**
 * @phpstan-import-type OptionalFileSizeOptionsType from FileSizeOptions
 * @phpstan-import-type RequiredFileSizeOptionsType from FileSizeOptions
 */
class FileSizeService
{
    /** @param OptionalFileSizeOptionsType $options */
    public function fromFile(
        string|UploadedFile|SplFileInfo $file,
        array $options = [],
    ): FileSize {
        if ($file instanceof UploadedFile) {
            $file = $file->getPathname();
        }

        return FileSize::fromFile($file, self::mergeOptions($options));
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function bytes(int|float $value, array $options = []): FileSize
    {
        return FileSize::fromBytes($value, self::mergeOptions($options));
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function kilobytes(int|float $value, array $options = []): FileSize
    {
        return FileSize::fromKilobytes($value, self::mergeOptions($options));
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function megabytes(int|float $value, array $options = []): FileSize
    {
        return FileSize::fromMegabytes($value, self::mergeOptions($options));
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function gigabytes(int|float $value, array $options = []): FileSize
    {
        return FileSize::fromGigabytes($value, self::mergeOptions($options));
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function terabytes(int|float $value, array $options = []): FileSize
    {
        return FileSize::fromTerabytes($value, self::mergeOptions($options));
    }

    /** @param OptionalFileSizeOptionsType $options */
    public static function petabytes(int|float $value, array $options = []): FileSize
    {
        return FileSize::fromPetabytes($value, self::mergeOptions($options));
    }

    /** @param array<int, mixed> $arguments */
    public function __call(string $method, array $arguments): FileSize
    {
        return $this->callCore($method, $arguments);
    }

    /** @param array<int, mixed> $arguments */
    public static function __callStatic(string $method, array $arguments): FileSize
    {
        return app(self::class)->callCore($method, $arguments);
    }

    /** @param array<int, mixed> $arguments */
    private function callCore(string $method, array $arguments): FileSize
    {
        if (! method_exists(FileSize::class, $method)) {
            throw new BadMethodCallException(
                "Method {$method} does not exist on FileSize",
            );
        }

        // Inject options if last argument is missing
        if (! $this->hasOptionsArgument($arguments)) {
            $arguments[] = self::mergeOptions($this->getOptionsArgument($arguments));
        }

        $options = end($arguments);

        return (new FileSize(options: $options))->$method(...$arguments);
    }

    /** @param array<int, mixed> $arguments */
    private function hasOptionsArgument(array $arguments): bool
    {
        $last = end($arguments);

        return is_array($last);
    }

    /**
     * @param array<int, mixed> $arguments
     * @return OptionalFileSizeOptionsType
     */
    private function getOptionsArgument(array $arguments): array
    {
        $last = end($arguments);

        return is_array($last) ? $last : [];
    }

    /**
     * @param OptionalFileSizeOptionsType $options
     * @return RequiredFileSizeOptionsType
     */
    private static function mergeOptions(array $options = []): array
    {
        return FileSizeConfigurationOptions::make($options)->toArray();
    }
}
