<?php

declare(strict_types=1);

namespace MartinCamen\FileSize\Tests;

use MartinCamen\FileSize\FileSizeServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            FileSizeServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'FileSize' => \MartinCamen\FileSize\Facades\FileSize::class,
        ];
    }
}
