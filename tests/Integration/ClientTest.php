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
     *
     */
    public function setUp()
    {
        $this->connector    = new Connector();

        $this->setupProperties();

    }


    /**
     *
     */
    public function tearDown()
    {
    }
}
