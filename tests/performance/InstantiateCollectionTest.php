<?php

/**
 * ArangoDB PHP Core Client Performance-Test-Suite: Performance Test for Collection Object instantiation using different methods
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140 as ArangoDbApi;

use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140\Collection;
use frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector;


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
        $connector    = new Connector();
        $this->client = getClient($connector);
    }


    public function testInstantiateCollectionsDirectly()
    {

        $startTime = microtime(true);

        for ($i = 1; $i <= 1000; $i++) {
            $collection[$i]         = new Collection();
            $collection[$i]->client = $this->client;
        }
        echo 'testInstantiateCollectionsDirectly() => Process time: ' . (microtime(
                    true
                ) - $startTime) . ' ms ' . PHP_EOL;
        unset ($collection);
    }


    public function testInstantiateCollectionsViaIocContainer()
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
        echo 'testInstantiateCollectionsViaIocContainer() => Process time: ' . (microtime(
                    true
                ) - $startTime) . ' ms ' . PHP_EOL;
        unset ($request);
    }
}
