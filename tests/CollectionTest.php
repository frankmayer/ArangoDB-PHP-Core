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
use frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector;


class CollectionTest extends
    \PHPUnit_Framework_TestCase
{

    protected $client;

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

        $collection = new ArangoDbApi\Collection($this->client);

        $responseObject = $collection->create($collectionName, $collectionOptions);
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $this->assertEquals(200, json_decode($body, true)['code']);
        $this->assertEquals($collectionName, json_decode($body, true)['name']);
    }


    /**
     * Test if we can get the server version
     */
    public function testTruncateCollection()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';


        $collection = new ArangoDbApi\Collection($this->client);

        $responseObject = $collection->truncate($collectionName);
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $this->assertEquals(200, json_decode($body, true)['code']);
        $this->assertEquals($collectionName, json_decode($body, true)['name']);
    }


    /**
     * Test if we can get the server version
     */
    public function testDeleteCollection()
    {

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';


        $collection = new ArangoDbApi\Collection($this->client);

        $responseObject = $collection->delete($collectionName);
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $this->assertEquals(200, json_decode($body, true)['code']);
    }


    /**
     * Test if we can get all collections
     */
    public function testGetCollections()
    {
        $collection = new ArangoDbApi\Collection($this->client);

        $responseObject = $collection->getAll();
        $response       = json_decode($responseObject->body);

        $this->assertObjectHasAttribute('_graphs', $response->names);
    }

    public function tearDown()
    {
    }
}
