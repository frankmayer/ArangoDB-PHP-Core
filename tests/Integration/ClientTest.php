<?php

/**
 * ArangoDB PHP Core Client Integration Test-Suite: Client Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Integration;

require_once __DIR__ . '/TestCase.php';

use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Client;

//todo: fix tests


/**
 * Class ClientTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class ClientTest extends TestCase
{

    /**
     * @var Client
     */
    public $client;
    /**
     * @var Connector
     */
    public $connector;


    /**
     * Test if the client returns an HttpResponse by default.
     *
     * Note: The behavior of this might be overridden, if a client is for example returning promises by default
     */
    public function testClientReturnsHttpResponseByDefault()
    {
        $collection     = new Collection($this->client);
        $responseObject = $collection->getAll();
        $responseObject = $this->resolveResponse($responseObject);

        static::assertInstanceOf('frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse', $responseObject);
    }
}
