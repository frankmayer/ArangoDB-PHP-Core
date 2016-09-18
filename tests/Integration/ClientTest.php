<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Client Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Integration;

require_once __DIR__.'/ArangoDbPhpCoreIntegrationTestCase.php';

use frankmayer\ArangoDbPhpCore\Client;

//todo: fix tests


/**
 * Class ClientTest
 * @package frankmayer\ArangoDbPhpCore
 */
class ClientIntegrationTest extends
    ArangoDbPhpCoreIntegrationTestCase
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
     *
     */
    public function setUp()
    {
        $connector       = new Connector();
        $this->connector = $connector;
        $this->client    = getClient($connector);
    }


    /**
     *
     */
    public function tearDown()
    {
    }
}
