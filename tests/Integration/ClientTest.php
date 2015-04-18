<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Client Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;

require_once('ArangoDbPhpCoreIntegrationTestCase.php');

use frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector;
use frankmayer\ArangoDbPhpCore\Plugins\TracerPlugin;

//todo: fix tests

/**
 * Class ClientTest
 * @package frankmayer\ArangoDbPhpCore
 */
class ClientTest extends ArangoDbPhpCoreIntegrationTestCase
{

    /**
     * @var Client
     */
    public $client;
    /**
     * @var \frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector
     */
    public $connector;


    /**
     *
     */
    public function setUp()
    {
        $connector       = new Connector();
        $this->connector = $connector;
        $this->client    = $this->client = getClient($connector);
    }


    /**
     * @return array
     */
    function setupClientWithPluginConfiguration()
    {

        $plugins = ['TracerPlugin' => new TracerPlugin()];

        return [
            ClientOptions::OPTION_ENDPOINT             => 'http://localhost:8529',
            ClientOptions::OPTION_DEFAULT_DATABASE     => '_system',
            ClientOptions::OPTION_TIMEOUT              => 5,
            ClientOptions::OPTION_PLUGINS              => $plugins,
            ClientOptions::OPTION_REQUEST_CLASS        => 'frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest',
            ClientOptions::OPTION_RESPONSE_CLASS       => 'frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse',
            ClientOptions::OPTION_ARANGODB_API_VERSION => '10400',

        ];
    }


    /**
     *
     */
    function testClientWithPluginConfiguration()
    {
        $client = new Client($this->connector, $this->setupClientWithPluginConfiguration());

        $this->assertArrayHasKey('TracerPlugin', $client->pluginManager->pluginStorage);
    }


    /**
     * @return array
     */
    function setupClientWithAuthenticationConfiguration()
    {

        return [
            ClientOptions::OPTION_ENDPOINT             => 'http://localhost:8529',
            ClientOptions::OPTION_DEFAULT_DATABASE     => '_system',
            ClientOptions::OPTION_TIMEOUT              => 5,
            ClientOptions::OPTION_REQUEST_CLASS        => 'frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest',
            ClientOptions::OPTION_RESPONSE_CLASS       => 'frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse',
            ClientOptions::OPTION_ARANGODB_API_VERSION => '10400',
            ClientOptions::OPTION_AUTH_TYPE            => 'Basic', // use basic authorization
            ClientOptions::OPTION_AUTH_USER            => 'coreTestUser', // user for basic authorization
            ClientOptions::OPTION_AUTH_PASSWD          => 'coreTestPassword', // password for basic authorization
        ];
    }


    // Can test this only if we set the server to actually authenticate (which is tested manually by the way... :D )
    //    /**
    //     * @expectedException     \frankmayer\ArangoDbPhpCore\ServerException
    //     */
    //    function testClientWithAuthenticationConfiguration()
    //    {
    //        $client = new Client($this->connector, $this->setupClientWithAuthenticationConfiguration());
    //
    //
    //        $request         = new $client->requestClass();
    //        $request->client = $client;
    //
    //        $request->path = $request->getDatabasePath() . Collection::API_COLLECTION;
    //
    //        $request->method = Api::METHOD_GET;
    //
    //
    //        $request->request();
    //    }


    /**
     * @expectedException     \frankmayer\ArangoDbPhpCore\ClientException
     */
    public function testMakeNonExistingType()
    {
        Client::make('nonExistingType');
    }


    //    simple getter/setter tests nothing fancy, only checking if the properties get set and are readable.
    /**
     *
     */
    public function testGettersSetters()
    {
        $testValue1 = $this->client->getArangoDBApiVersion();
        $this->assertNotEmpty($testValue1);

        $object = $this->client->setArangoDBApiVersion(10300);
        $this->assertTrue($this->client === $object);

        $testValue = $this->client->getArangoDBApiVersion();
        $this->assertEquals(10300, $testValue);

        $object = $this->client->setArangoDBApiVersion(10400);
        $this->assertTrue($this->client === $object);

        $testValue = $this->client->getArangoDBApiVersion();
        $this->assertEquals(10400, $testValue);


        $testValue1 = $this->client->getClientOptions();
        $this->assertNotEmpty($testValue1);

        $object = $this->client->setClientOptions(['testOption' => 'testVal']);
        $this->assertTrue($this->client === $object);

        $testValue = $this->client->getClientOptions();
        $this->assertArrayHasKey('testOption', $testValue);

        $object = $this->client->setClientOptions($testValue1);
        $this->assertTrue($this->client === $object);

        $testValue = $this->client->getClientOptions();
        $this->assertEquals($testValue1, $testValue);


        $testValue1 = $this->client->getConnector();
        $this->assertEquals($this->connector, $testValue1);

        $object = $this->client->setConnector($this->connector);
        $this->assertTrue($this->client === $object);

        $testValue1 = $this->client->getConnector();
        $this->assertEquals($this->connector, $testValue1);


        $testValue1 = $this->client->getDatabase();
        $this->assertNotEmpty($testValue1);

        $object = $this->client->setDatabase('testDB');
        $this->assertTrue($this->client === $object);

        $testValue = $this->client->getDatabase();
        $this->assertEquals('testDB', $testValue);

        $object = $this->client->setDatabase($testValue1);
        $this->assertTrue($this->client === $object);

        $testValue = $this->client->getDatabase();
        $this->assertEquals($testValue1, $testValue);


        $testValue1 = $this->client->getEndpoint();
        $this->assertNotEmpty($testValue1);

        $object = $this->client->setEndpoint('testEndpoint');
        $this->assertTrue($this->client === $object);

        $testValue = $this->client->getEndpoint();
        $this->assertEquals('testEndpoint', $testValue);

        $object = $this->client->setEndpoint($testValue1);
        $this->assertTrue($this->client === $object);

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
        $this->assertEquals('frankmayer\ArangoDbPhpCore\Protocols\Http\Request', $getRequestOriginalClass);

        $object = $this->client->setRequestClass('frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\RequestTest');
        $this->assertTrue($this->client === $object);

        $getRequestClass = $this->client->getRequestClass();
        $this->assertEquals('frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\RequestTest', $getRequestClass);

        $object = $this->client->setRequestClass($getRequestOriginalClass);
        $this->assertTrue($this->client === $object);

        $getRequestClass = $this->client->getRequestClass();
        $this->assertEquals('frankmayer\ArangoDbPhpCore\Protocols\Http\Request', $getRequestClass);

        $getResponseOriginalClass = $this->client->getResponseClass();
        $this->assertEquals('frankmayer\ArangoDbPhpCore\Protocols\Http\Response', $getResponseOriginalClass);

        $object = $this->client->setResponseClass('frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\ResponseTest');
        $this->assertTrue($this->client === $object);

        $getResponseClass = $this->client->getResponseClass();
        $this->assertEquals('frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\ResponseTest', $getResponseClass);

        $object = $this->client->setResponseClass($getResponseOriginalClass);
        $this->assertTrue($this->client === $object);

        $getResponseClass = $this->client->getResponseClass();
        $this->assertEquals('frankmayer\ArangoDbPhpCore\Protocols\Http\Response', $getResponseClass);
    }


    //    /**
    //     * @expectedException     \frankmayer\ArangoDbPhpCore\ServerException
    //     */
    //    public function testConnectorWrongEndpoint()
    //    {
    //        $collectionName         = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';
    //        $this->client->endpoint = 'http://127.0.0.113:12345';
    //        $collectionOptions      = ["waitForSync" => true];
    //
    //        $collection         = new ArangoDbApi\Collection();
    //        $collection->client = $this->client;
    //        $responseObject     = $collection->create($collectionName, $collectionOptions);
    //        $body               = $responseObject->body;
    //    }


    //    /**
    //     * @expectedException     \frankmayer\ArangoDbPhpCore\ServerException
    //     */
    //    public function testServerException()
    //    {
    //        $request         = new $this->client->requestClass();
    //        $request->client = $this->client;
    //
    //        $request->path = $request->getDatabasePath() . Collection::API_COLLECTION;
    //
    //        $request->method = Api::METHOD_PATCH;
    //
    //
    //        $request->request();
    //    }

    //    public function testHelperFunctionArray_merge_recursive_distinct()
    //    {
    //        $merged = Api::array_merge_recursive_distinct(
    //            ['key' => ['org value']],
    //            ['key' => ['new value']]
    //        );
    //        $this->assertEquals('new value', $merged['key'][0]);
    //    }
    //


    /**
     *
     */
    public function tearDown()
    {
    }
}
