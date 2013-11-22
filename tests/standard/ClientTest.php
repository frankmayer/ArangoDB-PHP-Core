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
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse;
use frankmayer\ArangoDbPhpCore\Plugins\TracerPlugin;


class ClientTest extends
    \PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    public $client;
    /**
     * @var \frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector
     */
    public $connector;


    public function setUp()
    {
        $connector       = new CurlHttpConnector();
        $this->connector = $connector;
        $this->client    = $this->client = getClient($connector);
    }

    function setupClientWithPluginConfiguration()
    {

        $plugins = array('TracerPlugin' => new TracerPlugin());

        return array(
            ClientOptions::OPTION_ENDPOINT             => 'http://localhost:8529',
            ClientOptions::OPTION_DEFAULT_DATABASE     => '_system',
            ClientOptions::OPTION_TIMEOUT              => 5,
            ClientOptions::OPTION_PLUGINS              => $plugins,
            ClientOptions::OPTION_REQUEST_CLASS        => 'frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest',
            ClientOptions::OPTION_RESPONSE_CLASS       => 'frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse',
            ClientOptions::OPTION_ARANGODB_API_VERSION => '10400',

        );
    }


    function testClientWithPluginConfiguration()
    {
        $client = new Client($this->connector, $this->setupClientWithPluginConfiguration());

        $this->assertArrayHasKey('TracerPlugin', $client->pluginManager->pluginStorage);
    }


    /**
     * @expectedException     \frankmayer\ArangoDbPhpCore\ClientException
     */
    public function testMakeNonExistingType()
    {
        Client::make('nonExistingType');
    }


    //    simple getter/setter tests nothing fancy, only checking if the properties get set and are readable.
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


        $testValue1 = $this->client->connector->getVerboseLogging();
        $this->assertFalse($testValue1);

        $this->client->connector->setVerboseLogging(true);

        $testValue = $this->client->connector->getVerboseLogging();
        $this->assertEquals(true, $testValue);

        $this->client->connector->setVerboseLogging($testValue1);

        $testValue = $this->client->connector->getVerboseLogging();
        $this->assertEquals($testValue1, $testValue);

        $getRequestOriginalClass = $this->client->getRequestClass();
        $this->assertEquals('frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest', $getRequestOriginalClass);

        $this->client->setRequestClass('frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequestTest');

        $getRequestClass = $this->client->getRequestClass();
        $this->assertEquals('frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequestTest', $getRequestClass);

        $this->client->setRequestClass($getRequestOriginalClass);

        $getRequestClass = $this->client->getRequestClass();
        $this->assertEquals('frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest', $getRequestClass);

        $getResponseOriginalClass = $this->client->getResponseClass();
        $this->assertEquals('frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse', $getResponseOriginalClass);

        $this->client->setResponseClass('frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponseTest');

        $getResponseClass = $this->client->getResponseClass();
        $this->assertEquals('frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponseTest', $getResponseClass);

        $this->client->setResponseClass($getResponseOriginalClass);

        $getResponseClass = $this->client->getResponseClass();
        $this->assertEquals('frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse', $getResponseClass);
    }

    /**
     * @expectedException     \frankmayer\ArangoDbPhpCore\ServerException
     */
    public function testConnectorWrongEndpoint()
    {

        $collectionName         = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';
        $this->client->endpoint = 'http://127.0.0.113:12345';
        $collectionOptions      = array("waitForSync" => true);

        $collection         = new ArangoDbApi\Collection();
        $collection->client = $this->client;
        $responseObject     = $collection->create($collectionName, $collectionOptions);
        $body               = $responseObject->body;
    }


    public function tearDown()
    {
    }
}
