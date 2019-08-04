<?php

namespace NStack\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use NStack\Config;

class TestCase extends \PHPUnit\Framework\TestCase
{

<<<<<<< HEAD
=======
        return json_decode($content, true);
    }

    /**
     * getClientWithMockedGet
     *
     * @param string $filename
     * @return \GuzzleHttp\Client
     * @author Casper Rasmussen <cr@nodes.dk>
     */
    protected function getClientWithMockedGet(string $filename): Client
    {
        $response = new Response(200, ['Content-Type' => 'application/json'],
            $this->getMockAsString($filename));

        $guzzle = \Mockery::mock(\GuzzleHttp\Client::class);
        $guzzle->shouldReceive('get')->once()->andReturn($response);

        return $guzzle;
    }

    /**
     * getMockAsString
     *
     * @param string $fileName
     * @return string
     * @author Casper Rasmussen <cr@nodes.dk>
     */
    protected function getMockAsString(string $fileName): string
    {
        return file_get_contents(getcwd() . '/tests/mocks/' . $fileName);
    }

    /**
     * getConfig
     *
     * @return \NStack\Config
     * @author Casper Rasmussen <cr@nodes.dk>
     */
    public function getConfig(): Config
    {
        return new Config('', '');
    }
>>>>>>> b56de27aae2dc30889c6f76338addbf1962027e9
}
