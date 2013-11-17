<?php

/**
 * ArangoDB PHP Core Client Performance-Test-Suite: Performance Test for HttpRequest Object instantiation using different methods
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140 as ArangoDbApi;

use frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest;


class InstantiateHttpRequestResolvedTest extends
    \PHPUnit_Framework_TestCase
{
    public $clientOptions;
    /**
     * @var Client
     */
    public $client;
    public $requestClass;
    public $loops;


    public function setUp()
    {
        $connector          = new CurlHttpConnector();
        $this->client       = getClient($connector);
        $this->requestClass = $this->client->requestClass;

        $this->loops = 1000;
    }


    public function testInstantiateRequestsThroughResolvingRequestClassProperty()
    {

        $startTime = microtime(true);

        for ($i = 1; $i <= $this->loops; $i++) {
            $request[] = new $this->requestClass($this->client);
        }
        echo 'testInstantiateRequestsThroughResolvingRequestClassProperty() => Process time: ' . (microtime(
                    true
                ) - $startTime) . ' ms ' . PHP_EOL;
        unset ($request);
    }
}
