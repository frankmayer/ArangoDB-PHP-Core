<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Batch Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140 as ArangoDbApi;

use frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse;


class BatchTest extends
    \PHPUnit_Framework_TestCase
{
    public $client;
    public $collectionNames;


    public function setUp()
    {
        $connector    = new CurlHttpConnector();
        $this->client = $this->client = getClient($connector);

        $this->collectionNames[0] = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection-01';
        $this->collectionNames[1] = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection-02';
        $this->collectionNames[2] = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection-03';
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateCollectionInBatchAndDeleteThemAgainInBatch()
    {
        $collectionOptions = array("waitForSync" => true);

        $batchParts = array();

        foreach ($this->collectionNames as $collectionName) {
            $collection         = new ArangoDbApi\Collection();
            $collection->client = $this->client;

            $batchPart = $collection->create(
                                    $collectionName,
                                    $collectionOptions,
                                    array('isBatchPart' => true)
            );
            $this->assertEquals(202, $batchPart->status);
            $batchParts[] = $batchPart;
        }


        $batch       = new ArangoDbApi\Batch();
        $batch->client = $this->client;

        $responseObject = $batch->send($batchParts);

        $batchResponseParts = $responseObject->batch;

        /** @var $batchPart HttpResponse */
        foreach ($batchResponseParts as $batchPart) {
            $body = $batchPart->body;
            $this->assertArrayHasKey('code', json_decode($body, true));
            $decodedJsonBody = json_decode($body, true);
            $this->assertEquals(200, $decodedJsonBody['code']);
        }

        $batchParts = array();

        foreach ($this->collectionNames as $collectionName) {
            $collection         = new ArangoDbApi\Collection();
            $collection->client = $this->client;
            $batchParts[]       = $collection->delete(
                                             $collectionName,
                                             array('isBatchPart' => true)
            );
        }

        $batch       = new ArangoDbApi\Batch();
        $batch->client = $this->client;

        $responseObject = $batch->send($batchParts);

        $batchResponseParts = $responseObject->batch;

        foreach ($batchResponseParts as $batchPart) {
            $body = $batchPart->body;
            $this->assertArrayHasKey('code', json_decode($body, true));
            $decodedJsonBody = json_decode($body, true);
            $this->assertEquals(200, $decodedJsonBody['code']);
        }
    }


    public function tearDown()
    {
        foreach ($this->collectionNames as $collectionName) {
            $collection         = new ArangoDbApi\Collection();
            $collection->client = $this->client;
            $batchParts[]       = $collection->delete(
                                             $collectionName
            );
        }
    }
}
