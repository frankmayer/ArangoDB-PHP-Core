<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Async Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140 as ArangoDbApi;
use frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse;


class ClientTest extends
    \PHPUnit_Framework_TestCase
{
    public $client;


    public function setUp()
    {
        $connector    = new CurlHttpConnector();
        $this->client = $this->client = getClient($connector);
    }

    /**
     * @expectedException     \frankmayer\ArangoDbPhpCore\ClientException
     */
    public function testMakeNonExistingType()
    {
        Client::make('nonExistingType');
    }

    public
    function tearDown()
    {
    }
}
