<?php

if (!function_exists('nstack')) {
    /**
     * nstack
     *
     * @return \NStack\NStack
     * @author Casper Rasmussen <cr@nodes.dk>
     */
    function nstack(): \NStack\NStack
    {
        return app('nstack');
    }
}