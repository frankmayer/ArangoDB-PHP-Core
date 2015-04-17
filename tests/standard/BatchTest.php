<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Batch Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


use frankmayer\ArangoDbPhpCore\Api\Rest\Batch;
use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector;
use frankmayer\ArangoDbPhpCore\Protocols\Http\Response;


/**
 * Class BatchTest
 * @package frankmayer\ArangoDbPhpCore
 */
class BatchTest extends
    \PHPUnit_Framework_TestCase
{
    /**
     * @var
     */
    public $client;
    /**
     * @var
     */
    public $collectionNames;


    /**
     *
     */
    public function setUp()
    {
        $connector    = new Connector();
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
        $collectionOptions = ["waitForSync" => true];

        $batchParts = [];

        foreach ($this->collectionNames as $collectionName) {
            $collection         = new Collection();
            $collection->client = $this->client;

            $batchPart = Collection::create($this->client, $collectionName, $collectionOptions,
                ['isBatchPart' => true]);

            $this->assertEquals(202, $batchPart->status);
            $batchParts[] = $batchPart;
        }


        $batch         = new Batch();
        $batch->client = $this->client;

        /** @var Response $responseObject */
        $responseObject = $batch->send($batchParts);
        $this->assertEquals(200, $responseObject->status);

        $batchResponseParts = $responseObject->batch;

        /** @var $batchPart Response */
        foreach ($batchResponseParts as $batchPart) {
            $body = $batchPart->body;
            $this->assertArrayHasKey('code', json_decode($body, true));
            $decodedJsonBody = json_decode($body, true);
            $this->assertEquals(200, $decodedJsonBody['code']);
        }

        $batchParts = [];

        foreach ($this->collectionNames as $collectionName) {
            $collection         = new Collection();
            $collection->client = $this->client;

            $batchParts[] = Collection::delete($this->client, $collectionName, ['isBatchPart' => true]);
        }

        $batch         = new Batch();
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


    /**
     *
     */
    public function tearDown()
    {
        foreach ($this->collectionNames as $collectionName) {
            $batchParts[] = Collection::delete($this->client, $collectionName, ['isBatchPart' => true]);
        }
    }
}
