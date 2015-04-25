<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Collection Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Integration;

require_once('ArangoDbPhpCoreIntegrationTestCase.php');

use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Client;
use HttpResponse;

//todo: fix tests

/**
 * Class CollectionTest
 * @package frankmayer\ArangoDbPhpCore
 */
class CollectionIntegrationTest extends
    ArangoDbPhpCoreIntegrationTestCase
{
    /**
     * @var Client
     */
    public $client;


    /**
     *
     */
    public function setUp()
    {
        $connector    = new Connector();
        $this->client = $this->client = getClient($connector);
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateCollectionWithoutApiClasses()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions    = ["waitForSync" => true];
        $collectionParameters = [];
        $options              = $collectionOptions;
        $this->client->bind(
            'Request',
            function () {
                $request         = new $this->client->requestClass($this);
                $request->client = $this->client;

                return $request;
            }
        );

        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $request          = $this->client->make('Request');
        $request->options = $options;
        $request->body    = ['name' => $collectionName];

        $request->body = self::array_merge_recursive_distinct($request->body, $collectionParameters);
        $request->body = json_encode($request->body);

        $request->path   = $this->client->fullDatabasePath . self::API_COLLECTION;
        $request->method = self::METHOD_POST;

        $responseObject = $request->send();

        $body = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);
    }


    /**
     * Test if we can get the server version
     */
    public function testDeleteCollectionWithoutApiClasses()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions = ["waitForSync" => true];
        $options           = $collectionOptions;
        $this->client->bind(
            'Request',
            function () {
                $request         = new $this->client->requestClass($this);
                $request->client = $this->client;

                return $request;
            }
        );


        $request = $this->client->make('Request');

        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . self::API_COLLECTION . '/' . $collectionName;
        $request->method  = self::METHOD_DELETE;

        $responseObject = $request->send();
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateCollectionViaIocContainer()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions = ["waitForSync" => true];


        $collection         = new Collection($this->client);
        $collection->client = $this->client;

        /** @var $responseObject HttpResponse */
        $responseObject = $collection->create($collectionName, $collectionOptions);

        $body = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);
    }


    /**
     * Test if we can get the server version
     */
    public function testTruncateCollection()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collection         = new Collection($this->client);
        $collection->client = $this->client;

        /** @var $responseObject HttpResponse */
        $responseObject = $collection->truncate($collectionName);

        $body = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);
    }


    /**
     * Test if we can get the server version
     */
    public function testDeleteCollection()
    {

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collection         = new Collection($this->client);
        $collection->client = $this->client;

        /** @var $responseObject HttpResponse */
        $responseObject = $collection->drop($collectionName);

        $body = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
    }


    /**
     * Test if we can get all collections
     */
    public function testGetCollections()
    {
        $collection         = new Collection($this->client);
        $collection->client = $this->client;

        /** @var $responseObject HttpResponse */
        $responseObject = $collection->getAll();

        $response = json_decode($responseObject->body);

        $this->assertObjectHasAttribute('_graphs', $response->names);
    }


    /**
     * Test if we can get all collections
     */
    public function testGetCollectionsExcludeSystem()
    {
        $collection         = new Collection($this->client);
        $collection->client = $this->client;

        /** @var $responseObject HttpResponse */
        $responseObject = $collection->getAll(['excludeSystem' => true]);

        $response = json_decode($responseObject->body);

        $this->assertObjectNotHasAttribute('_graphs', $response->names);
    }


    /**
     *
     */
    public function tearDown()
    {
        $collectionName     = 'ArangoDB-PHP-Core-CollectionTestSuite-CollectionViaIocContainer';
        $collection         = new Collection($this->client);
        $collection->client = $this->client;

        /** @var $responseObject HttpResponse */
        $collection->drop($collectionName);
    }
}