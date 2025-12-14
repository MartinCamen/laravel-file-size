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
