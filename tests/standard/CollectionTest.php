<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Collection Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140 as ArangoDbApi;
use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140\Collection;
use frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector;


class CollectionTest extends
    \PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    public $client;

    public function setUp()
    {
        $connector    = new CurlHttpConnector();
        $this->client = $this->client = getClient($connector);
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateCollection()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions = array("waitForSync" => true);

        $collection         = new ArangoDbApi\Collection();
        $collection->client = $this->client;
        $responseObject = $collection->create($collectionName, $collectionOptions);
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);
    }


   /**
     * Test if we can get the server version
     */
    public function testCreateCollectionViaIocContainer()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-CollectionViaIocContainer';

        $collectionOptions = array("waitForSync" => true);


        // Here's how a binding for the HttpRequest should take place in the IOC container.
        // The actual binding should only happen once in the client construction, though. This is only for testing...

        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            // This is the way to bind an HttpRequest in PHP 5.4+

            Client::bind(
                         'ArangoCollection',
                             function () {
                                 $instance = new ArangoDbApi\Collection();
                                 $instance->client=$this->client;
                                 return $instance;
                             }
            );
        } else {
            // This is the way to bind an HttpRequest in PHP 5.3.x

            $me = $this;
            Client::bind(
                         'ArangoCollection',
                             function () use ($me) {
                                 $instance = new ArangoDbApi\Collection();
                                 $instance->client=$me->client;
                                 return $instance;
                             }
            );
        }
        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $collection = Client::make('ArangoCollection');


//        $collection = new ArangoDbApi\Collection($this->client);

        /** @var $collection Collection */
        $responseObject = $collection->create($collectionName, $collectionOptions);
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);
        $collection->delete($collectionName);
    }


    /**
     * Test if we can get the server version
     */
    public function testTruncateCollection()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';


        $collection         = new ArangoDbApi\Collection();
        $collection->client = $this->client;
        $responseObject = $collection->truncate($collectionName);
        $body           = $responseObject->body;

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


        $collection         = new ArangoDbApi\Collection();
        $collection->client = $this->client;
        $responseObject = $collection->delete($collectionName);
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
    }


    /**
     * Test if we can get all collections
     */
    public function testGetCollections()
    {
        $collection         = new ArangoDbApi\Collection();
        $collection->client = $this->client;
        $responseObject = $collection->getAll();
        $response       = json_decode($responseObject->body);

        $this->assertObjectHasAttribute('_graphs', $response->names);
    }

    public function tearDown()
    {
    }
}
