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

use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector;

//todo: fix tests

/**
 * Class CollectionTest
 * @package frankmayer\ArangoDbPhpCore
 */
class CollectionTest extends
    ArangoDbPhpCoreTestCase
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


    /**
     * Test if we can get the server version
     */
    public function testCreateCollectionViaIocContainer()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions = ["waitForSync" => true];

        // Here's how a binding for the HttpRequest should take place in the IOC container.
        // The actual binding should only happen once in the client construction, though. This is only for testing...
        Client::bind(
            'Collection',
            function () {
                $object         = new Collection();
                $object->client = $this->client;

                return $object;
            }
        );

        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)

        /** @var $collection Collection */
        $responseObject = Collection::create($this->client, $collectionName, $collectionOptions);

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

        $responseObject = Collection::truncate($this->client, $collectionName);

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

        $responseObject = Collection::delete($this->client, $collectionName);

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
        $responseObject = Collection::getAll($this->client);

        $response = json_decode($responseObject->body);

        $this->assertObjectHasAttribute('_graphs', $response->names);
    }


    /**
     * Test if we can get all collections
     */
    public function testGetCollectionsExcludeSystem()
    {
        $responseObject = Collection::getAll($this->client, ['excludeSystem' => true]);

        $response = json_decode($responseObject->body);

        $this->assertObjectNotHasAttribute('_graphs', $response->names);
    }


    /**
     *
     */
    public function tearDown()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-CollectionViaIocContainer';

        Collection::delete($this->client, $collectionName);
    }
}
