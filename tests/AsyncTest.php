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


class AsyncTest extends
    \PHPUnit_Framework_TestCase
{
    protected $client;


    public function setUp()
    {
        $connector    = new CurlHttpConnector();
        $this->client = $this->client = getClient($connector);
    }


    public function testCreateCollectionAndSimpleAsyncDocumentCreation()
    {

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions = array("waitForSync" => true);

        $collection = new ArangoDbApi\Collection($this->client);

        $responseObject = $collection->create($collectionName, $collectionOptions);
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $requestBody    = array('name' => 'frank', '_key' => '1');
        $document       = new ArangoDbApi\Document($this->client);
        $responseObject = $document->create($collectionName, $requestBody, null, array('async' => true));

        /** @var $responseObject HttpResponse */
        $this->assertEquals(202, $responseObject->status);


        // todo 1 Frank test if the document was inserted, by checking again after a second

        sleep(1);

        $document       = new ArangoDbApi\Document($this->client);
        $responseObject = $document->get($collectionName . '/1', $requestBody);

        $responseBody = $responseObject->body;
        $this->assertEquals($collectionName . '/1', json_decode($responseBody, true)['_id']);
    }

    public function testCreateCollectionAndStoredAsyncDocumentCreation()
    {

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions = array("waitForSync" => true);

        $collection = new ArangoDbApi\Collection($this->client);

        $responseObject = $collection->create($collectionName, $collectionOptions);
        $body           = $responseObject->body;

        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $requestBody    = array('name' => 'frank', '_key' => '1');
        $document       = new ArangoDbApi\Document($this->client);
        $responseObject = $document->create($collectionName, $requestBody, null, array('async' => 'store'));

        /** @var $responseObject HttpResponse */
        $this->assertEquals(202, $responseObject->status);


        // todo 1 Frank test if the document was inserted, by checking again after a second

        sleep(1);

        $document       = new ArangoDbApi\Document($this->client);
        $responseObject = $document->get($collectionName . '/1', $requestBody);

        $responseBody    = $responseObject->body;
        $decodedJsonBody = json_decode($responseBody, true);
        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);
    }


    public function tearDown()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';


        $collection = new ArangoDbApi\Collection($this->client);

        $collection->delete($collectionName);
    }
}
