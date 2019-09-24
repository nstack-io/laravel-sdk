<?php

namespace NStack\Laravel\Tests;

use Illuminate\Support\Facades\Storage;
use NStack\Config;
use NStack\NStack;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function mockStorage()
    {
        Storage::extend('mock', function () {
            return \Mockery::mock(\Illuminate\Contracts\Filesystem\Filesystem::class);
        });

        Config::set('filesystems.disks.mock', ['driver' => 'mock']);
        Config::set('filesystems.default', 'mock');

        return Storage::disk();
    }

    protected function mockNstack()
    {
        return new NStack()
    }
}
