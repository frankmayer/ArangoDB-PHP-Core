<?php

/**
 * ArangoDB PHP Core Client Integration Test-Suite: Batch Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Integration;

require_once __DIR__ . '/TestCase.php';

use frankmayer\ArangoDbPhpCore\Api\Rest\Batch;
use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse;


/**
 * Class BatchTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class BatchTest extends TestCase
{
    /**
     * @var
     */
    public $collectionNames;


    /**
     *
     */
    public function setUp()
    {
        $this->connector = new Connector();

        $this->setupProperties();

        $this->collectionNames[0] = $this->TESTNAMES_PREFIX . 'CollectionTestSuite-Collection-01';
        $this->collectionNames[1] = $this->TESTNAMES_PREFIX . 'CollectionTestSuite-Collection-02';
        $this->collectionNames[2] = $this->TESTNAMES_PREFIX . 'CollectionTestSuite-Collection-03';
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateCollectionInBatchAndDeleteThemAgainInBatch()
    {
        $collectionOptions = ['waitForSync' => true];

        $batchParts = [];

        foreach ($this->collectionNames as $collectionName) {
            $collection = new Collection($this->client);

            /** @var $responseObject HttpResponse */
            $batchPart = $collection->create($collectionName, $collectionOptions, ['isBatchPart' => true]);

            $batchParts[] = $batchPart;
        }

        /** @var HttpResponse $responseObject */
        $batch          = new Batch($this->client);
        $responseObject = $batch->send($this->client, $batchParts);
        $responseObject = $this->resolveResponse($responseObject);
        $this->assertEquals(200, $responseObject->status);

        $batchResponseParts = $responseObject->batch;

        /** @var $batchPart HttpResponse */
        foreach ($batchResponseParts as $batchPart) {
            $body = $batchPart->body;
            $this->assertArrayHasKey('code', json_decode($body, true));
            $decodedJsonBody = json_decode($body, true);
            $this->assertEquals(200, $decodedJsonBody['code']);
        }

        $batchParts = [];

        foreach ($this->collectionNames as $collectionName) {
            $collection = new Collection($this->client);

            /** @var $responseObject HttpResponse */
            $batchParts[] = $collection->drop($collectionName, ['isBatchPart' => true]);
        }

        $batch          = new Batch($this->client);
        $responseObject = $batch->send($this->client, $batchParts);

        $batchResponseParts = $responseObject->batch;

        foreach ($batchResponseParts as $batchPart) {
            $body = $batchPart->body;
            $this->assertArrayHasKey('code', json_decode($body, true));
            $decodedJsonBody = json_decode($body, true);
            $this->assertEquals(200, $decodedJsonBody['code']);
        }
    }


    /**
     *
     */
    public function tearDown()
    {
        $batchParts = [];
        foreach ($this->collectionNames as $collectionName) {
            $collection = new Collection($this->client);

            /** @var $responseObject HttpResponse */
            $batchParts[] = $collection->drop($collectionName, ['isBatchPart' => true]);
        }
        $batch = new Batch($this->client);
        $result = $batch->send($this->client, $batchParts);
        $result = $this->resolveResponse($result);
    }
}
