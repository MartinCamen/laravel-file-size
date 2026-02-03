<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use MartinCamen\FileSize\Services\FileSizeService;
use MartinCamen\PhpFileSize\FileSize as PhpFileSize;

it('creates FileSize from UploadedFile instance', function (): void {
    // Create a real temp file with known content
    $tempFile = tempnam(sys_get_temp_dir(), 'test');
    file_put_contents($tempFile, str_repeat('a', 512));

    try {
        // Create an UploadedFile from the real temp file
        $uploadedFile = new UploadedFile($tempFile, 'test.txt', null, null, true);

        $service = new FileSizeService();
        $result = $service->fromFile($uploadedFile);

        expect($result)
            ->toBeInstanceOf(PhpFileSize::class)
            ->and($result->toBytes())
            ->toBe(512.0);
    } finally {
        @unlink($tempFile);
    }
});

it('creates FileSize from string path', function (): void {
    $tempFile = tempnam(sys_get_temp_dir(), 'test');
    file_put_contents($tempFile, str_repeat('a', 1024));

    try {
        $service = new FileSizeService();
        $result = $service->fromFile($tempFile);

        expect($result)
            ->toBeInstanceOf(PhpFileSize::class)
            ->and($result->toBytes())
            ->toBe(1024.0);
    } finally {
        unlink($tempFile);
    }
});

it('creates FileSize from SplFileInfo instance', function (): void {
    $tempFile = tempnam(sys_get_temp_dir(), 'test');
    file_put_contents($tempFile, str_repeat('a', 2048));

    try {
        $service = new FileSizeService();
        $result = $service->fromFile(new SplFileInfo($tempFile));

        expect($result)
            ->toBeInstanceOf(PhpFileSize::class)
            ->and($result->toBytes())
            ->toBe(2048.0);
    } finally {
        unlink($tempFile);
    }
});

it('passes options when creating FileSize from file', function (): void {
    $uploadedFile = UploadedFile::fake()->create('test.txt', 100);

    $service = new FileSizeService();
    $result = $service->fromFile($uploadedFile, ['precision' => 5]);

    expect($result)
        ->toBeInstanceOf(PhpFileSize::class)
        ->and($result->getPrecision())
        ->toBe(5);
});
