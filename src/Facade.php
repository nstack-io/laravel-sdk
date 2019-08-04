<?php

namespace NStack;

/**
 * Class Assets Facade.
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @author Casper Rasmussen <cr@nodes.dk>
     */
    protected static function getFacadeAccessor()
    {
        return 'nstack';
    }
}