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

use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140\Collection;
use frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest;


/*
 * These Notes are for test that ran on PHP 5.4.x
 * Notes: The instantiation of the HttpRequest object is about the same between direct naming and through resolving Client::requestClass
 * The small difference is due to PHP internal optimization, but whichever of both gets tested first is a little slower than the second.
 * Even when swapping the tests.
 *
 * By instantiating through the IOC container, the time doubles, but it shouldn't have considerable impact on overall performance.
 * If someone needs to squeeze a bit more out of it, then the IOC container can be avoided and the instantiation can be made directly or via
 * resolving the Client:requestClass
 *
 */


class InstantiateCollectionTest extends
    \PHPUnit_Framework_TestCase
{
    public $clientOptions;
    /**
     * @var Client
     */
    public $client;
    public $requestClass;


    public function setUp()
    {
        $connector          = new CurlHttpConnector();
        $this->client       = getClient($connector);
        $this->requestClass = $this->client->requestClass;
    }


    public function testInstantiateRequestsDirectly()
    {

        $startTime = microtime(true);

        for ($i = 1; $i <= 1000; $i++) {
            $collection[$i]         = new Collection();
            $collection[$i]->client = $this->client;
        }
        echo 'testInstantiateRequestsDirectly() => Process time: ' . (microtime(true) - $startTime) . ' ms ' . PHP_EOL;
        unset ($collection);
    }


    public function testInstantiateRequestsViaIocContainer()
    {
        // Here's how a binding for the HttpRequest should take place in the IOC container.
        // The actual binding should only happen once in the client construction, though. This is only for testing...

        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            // This is the way to bind an HttpRequest in PHP 5.4+

            Client::bind(
                  'ArangoCollection',
                      function () {
                          $instance         = new ArangoDbApi\Collection();
                          $instance->client = $this->client;

                          return $instance;
                      }
            );
        } else {
            // This is the way to bind an HttpRequest in PHP 5.3.x

            $me = $this;
            Client::bind(
                  'ArangoCollection',
                      function () use ($me) {
                          $instance         = new ArangoDbApi\Collection();
                          $instance->client = $me->client;

                          return $instance;
                      }
            );
        }
        $startTime = microtime(true);

        for ($i = 1; $i <= 1000; $i++) {
            $request[] = Client::make('ArangoCollection');
        }
        echo 'testInstantiateRequestsViaIocContainer() => Process time: ' . (microtime(
                    true
                ) - $startTime) . ' ms ' . PHP_EOL;
        unset ($request);
    }
}
