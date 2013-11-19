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

    /**
     * @var Client
     */
    public $client;
    public $connector;


    public function setUp()
    {
        $connector       = new CurlHttpConnector();
        $this->connector = $connector;
        $this->client    = $this->client = getClient($connector);
    }

    /**
     * @expectedException     \frankmayer\ArangoDbPhpCore\ClientException
     */
    public function testMakeNonExistingType()
    {
        Client::make('nonExistingType');
    }


    public function testGettersSetters()
    {
        $testValue1 = $this->client->getArangodbApiVersion();
        $this->assertNotEmpty($testValue1);

        $this->client->setArangodbApiVersion(10300);

        $testValue = $this->client->getArangodbApiVersion();
        $this->assertEquals(10300, $testValue);

        $this->client->setArangodbApiVersion(10400);

        $testValue = $this->client->getArangodbApiVersion();
        $this->assertEquals(10400, $testValue);


        $testValue1 = $this->client->getClientOptions();
        $this->assertNotEmpty($testValue1);

        $this->client->setClientOptions(array('testOption' => 'testVal'));

        $testValue = $this->client->getClientOptions();
        $this->assertArrayHasKey('testOption', $testValue);

        $this->client->setClientOptions($testValue1);

        $testValue = $this->client->getClientOptions();
        $this->assertEquals($testValue1, $testValue);


        $testValue1 = $this->client->getConnector();
        $this->assertEquals($this->connector, $testValue1);

        $this->client->setConnector($this->connector);

        $testValue1 = $this->client->getConnector();
        $this->assertEquals($this->connector, $testValue1);


        $testValue1 = $this->client->getDatabase();
        $this->assertNotEmpty($testValue1);

        $this->client->setDatabase('testDB');

        $testValue = $this->client->getDatabase();
        $this->assertEquals('testDB', $testValue);

        $this->client->setDatabase($testValue1);

        $testValue = $this->client->getDatabase();
        $this->assertEquals($testValue1, $testValue);

        
        $testValue1 = $this->client->getEndpoint();
        $this->assertNotEmpty($testValue1);

        $this->client->setEndpoint('testEndpoint');

        $testValue = $this->client->getEndpoint();
        $this->assertEquals('testEndpoint', $testValue);

        $this->client->setEndpoint($testValue1);

        $testValue = $this->client->getEndpoint();
        $this->assertEquals($testValue1, $testValue);
    }

    public
    function tearDown()
    {
    }
}