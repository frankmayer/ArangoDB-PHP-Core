<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Plugin Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140 as ArangoDbApi;

use frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest;



class Performance01Test extends
    \PHPUnit_Framework_TestCase
{
    protected $clientOptions;
    protected $client;


    public function setUp()
    {
        $connector    = new CurlHttpConnector();
        $this->client = getClient($connector);
    }

    // todo 1 Frank Complete plugin tests
    /**
     * Test if we can get the server version
     */


    public function testInstantiateRequestsDirectly()
    {

        $startTime = microtime(true);

        for ($i = 1; $i <= 1000; $i++) {
            $request[] = new HttpRequest($this->client);
        }
        echo 'testInstantiateRequestsDirectly() => Process time: ' . (microtime(true) - $startTime) . ' ms ' . PHP_EOL;
        unset ($request);
    }


    public function testInstantiateRequestsViaIocContainer()
    {
        $this->client->bind(
                     'httpRequest',
                         function () {
                             return new HttpRequest($this->client);
                         }
        );
        $startTime = microtime(true);

        for ($i = 1; $i <= 1000; $i++) {
            $request[] = $this->client->make('httpRequest');
        }
        echo 'testInstantiateRequestsViaIocContainer() => Process time: ' . (microtime(
                    true
                ) - $startTime) . ' ms ' . PHP_EOL;
        unset ($request);
    }


    public function tearDown()
    {
    }
}
