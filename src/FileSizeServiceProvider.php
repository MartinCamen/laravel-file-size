<?php

namespace MartinCamen\FileSize;

use MartinCamen\PhpFileSize\Configuration\FileSizeOptions;
use MartinCamen\PhpFileSize\FileSize;
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

    public function packageRegistered(): void
    {
        $this->app->bind('file-size', function (): FileSize {
            $options = new FileSizeOptions(
                byteBase: config()->string('file-size.byte_base') ?: null,
                precision: config()->integer('file-size.precision'),
                labelStyle: config('file-size.formatting.label_style') ?: null,
                decimalSeparator: config()->string('file-size.formatting.decimal_separator'),
                thousandsSeparator: config()->string('file-size.formatting.thousands_separator'),
                spaceBetweenValueAndUnit: config()->boolean('file-size.formatting.space_between_value_and_unit'),
                validationThrowOnNegativeResult: config()->boolean('file-size.validation.throw_on_negative_result'),
                validationAllowNegativeInput: config()->boolean('file-size.validation.allow_negative_input'),
            );

            return new FileSize(options: $options->toArray());
        });

        $this->app->alias('file-size', FileSize::class);
    }
}
