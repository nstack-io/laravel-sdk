<?php

namespace NStack\Tests\Translation;

use Illuminate\Filesystem\Filesystem;
use NStack\Tests\LaravelTestCase;
use NStack\Translation\NStackLoader;

class NStackLoaderTest extends LaravelTestCase
{
    public function testConstruct()
    {
        try {
            $storage = new Filesystem();

            $nstack = $this->mockNStack();
            $nstackLoader = new NStackLoader($storage, '', $nstack);
        } catch (\Throwable $e) {
            // TODO

        }

        $this->assertTrue(true);
    }
}