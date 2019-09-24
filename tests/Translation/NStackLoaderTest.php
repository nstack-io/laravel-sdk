<?php

namespace NStack\Tests\Translation;

use League\Flysystem\Filesystem;
use NStack\Translation\NStackLoader;

class NStackLoaderTest extends \NStack\Laravel\Tests\TestCase
{
    public function testConstruct()
    {
        $storage = $this->mockStorage();

        $nstackLoader = new NStackLoader($storage, '');


    }
}