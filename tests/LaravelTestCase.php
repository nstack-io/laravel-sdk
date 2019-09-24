<?php

namespace NStack\Tests;

use NStack\Config;
use NStack\NStack;

class LaravelTestCase extends \PHPUnit\Framework\TestCase
{
    protected function mockNStack()
    {
        return new NStack(new Config('', ''));
    }
}
