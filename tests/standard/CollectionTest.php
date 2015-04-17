<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Collection Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;

require_once('ArangoDbPhpCoreTestCase.php');

use frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector;

//todo: fix tests

class CollectionTest extends
    ArangoDbPhpCoreTestCase
{
    /**
     * @var Client
     */
    public $client;

    public function setUp()
    {
        $connector    = new Connector();
        $this->client = $this->client = getClient($connector);
    }

    /**
     * Test if we can get the server version
     */
    public function testCreateCollection()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions    = ["waitForSync" => true];
        $collectionParameters = [];
        $options              = $collectionOptions;
        Client::bind(
            'Request',
            function () {
                $request         = new $this->client->requestClass();
                $request->client = $this->client;

                return $request;
            }
        );

        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $request          = Client::make('Request');
        $request->options = $options;
        $request->body    = ['name' => $collectionName];

        $request->body = self::array_merge_recursive_distinct($request->body, $collectionParameters);
        $request->body = json_encode($request->body);

        $request->path   = $request->getDatabasePath() . self::API_COLLECTION;
        $request->method = self::METHOD_POST;

        $responseObject = $request->send();

        //        return $responseObject;
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

        $collectionOptions    = ["waitForSync" => true];
        $collectionParameters = [];
        $options              = $collectionOptions;
        Client::bind(
            'Request',
            function () {
                $request         = new $this->client->requestClass();
                $request->client = $this->client;

                return $request;
            }
        );

        $request = Client::make('Request');

        $request->options = $options;
        $request->path    = $request->getDatabasePath() . self::API_COLLECTION . '/' . $collectionName;
        $request->method  = self::METHOD_DELETE;

        $responseObject = $request->send();
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
    }



    //
    //    /**
    //     * Test if we can get the server version
    //     */
    //    public function testCreateCollectionViaIocContainer()
    //    {
    //        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-CollectionViaIocContainer';
    //
    //        $collectionOptions = ["waitForSync" => true];
    //
    //
    //        // Here's how a binding for the HttpRequest should take place in the IOC container.
    //        // The actual binding should only happen once in the client construction, though. This is only for testing...
    //
    //
    //        // And here's how one gets an HttpRequest object through the IOC.
    //        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
    //        $collection = Client::make('ArangoCollection');
    //
    //        /** @var $collection Collection */
    //        $responseObject = $collection->create($collectionName, $collectionOptions);
    //        $body           = $responseObject->body;
    //
    //        $this->assertArrayHasKey('code', json_decode($body, true));
    //        $decodedJsonBody = json_decode($body, true);
    //        $this->assertEquals(200, $decodedJsonBody['code']);
    //        $this->assertEquals($collectionName, $decodedJsonBody['name']);
    //    }
    //
    //
    //    /**
    //     * Test if we can get the server version
    //     */
    //    public function testTruncateCollection()
    //    {
    //        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';
    //
    //
    //        $collection         = new ArangoDbApi\Collection();
    //        $collection->client = $this->client;
    //        $responseObject     = $collection->truncate($collectionName);
    //        $body               = $responseObject->body;
    //
    //        $this->assertArrayHasKey('code', json_decode($body, true));
    //        $decodedJsonBody = json_decode($body, true);
    //        $this->assertEquals(200, $decodedJsonBody['code']);
    //        $this->assertEquals($collectionName, $decodedJsonBody['name']);
    //    }
    //
    //
    //    /**
    //     * Test if we can get the server version
    //     */
    //    public function testDeleteCollection()
    //    {
    //
    //        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';
    //
    //
    //        $collection         = new ArangoDbApi\Collection();
    //        $collection->client = $this->client;
    //        $responseObject     = $collection->delete($collectionName);
    //        $body               = $responseObject->body;
    //
    //        $this->assertArrayHasKey('code', json_decode($body, true));
    //        $decodedJsonBody = json_decode($body, true);
    //        $this->assertEquals(200, $decodedJsonBody['code']);
    //    }
    //
    //
    //    /**
    //     * Test if we can get all collections
    //     */
    //    public function testGetCollections()
    //    {
    //        $collection         = new ArangoDbApi\Collection();
    //        $collection->client = $this->client;
    //        $responseObject     = $collection->getAll();
    //        $response           = json_decode($responseObject->body);
    //
    //        $this->assertObjectHasAttribute('_graphs', $response->names);
    //    }
    //
    //    /**
    //     * Test if we can get all collections
    //     */
    //    public function testGetCollectionsExcludeSystem()
    //    {
    //        $collection         = new ArangoDbApi\Collection();
    //        $collection->client = $this->client;
    //        $responseObject     = $collection->getAll(['excludeSystem' => true]);
    //        $response           = json_decode($responseObject->body);
    //
    //        $this->assertObjectNotHasAttribute('_graphs', $response->names);
    //    }
    //
    //    public function tearDown()
    //    {
    //        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-CollectionViaIocContainer';
    //
    //        $collection = Client::make('ArangoCollection');
    //        $collection->delete($collectionName);
    //    }


}
