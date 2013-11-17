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

class InstantiateHttpRequestIocTest extends
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

        for ($i = 1; $i <= $this->loops; $i++) {
            $request[] = Client::make('ArangoCollection');
        }
        echo 'testInstantiateRequestsViaIocContainer() => Process time: ' . (microtime(
                    true
                ) - $startTime) . ' ms ' . PHP_EOL;
        unset ($request);
    }
}
