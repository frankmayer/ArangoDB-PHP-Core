<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Async Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;

require_once('ArangoDbPhpCoreIntegrationTestCase.php');

use frankmayer\ArangoDbPhpCore\Api\Rest\Async;
use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Api\Rest\Document;
use frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector;
use frankmayer\ArangoDbPhpCore\Protocols\Http\Response;


/**
 * Class AsyncTest
 * @package frankmayer\ArangoDbPhpCore
 */
class AsyncTest extends ArangoDbPhpCoreIntegrationTestCase
{
    /**
     * @var Client $client
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
     *
     */
    public function testCreateCollectionAndSimpleAsyncDocumentCreation()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions = ["waitForSync" => true];

        /** @var $responseObject Response */
        $responseObject = Collection::create($this->client, $collectionName, $collectionOptions);
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $requestBody = ['name' => 'frank', '_key' => '1'];

        $responseObject = Document::create($this->client, $collectionName, $requestBody, null, ['async' => true]);

        $this->assertEquals(202, $responseObject->status);

        sleep(1);

        $responseObject = Document::get($this->client, $collectionName . '/1', $requestBody);

        $responseBody    = $responseObject->body;
        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);
    }

    /**
     *
     */
    public function testCreateCollectionAndStoredAsyncDocumentCreation()
    {
        $jobDeleteResponse = Async::deleteJobResult($this->client, 'all');

        // todo 1 Frank Write real test for deleting job results with stamp
        $jobDeleteResponse = Async::deleteJobResult($this->client, 'all', time());

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions = ["waitForSync" => true];

        /** @var $responseObject Response */
        $responseObject = Collection::create($this->client, $collectionName, $collectionOptions);

        $body = $responseObject->body;

        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $requestBody = ['name' => 'frank', '_key' => '1'];

        $responseObject = Document::create($this->client, $collectionName, $requestBody, null, ['async' => 'store']);

        $this->assertEquals(202, $responseObject->status);

        sleep(1);

        $jobId   = $responseObject->headers['x-arango-async-id'][0];
        $jobList = Async::listJobResults($this->client, 'done', 1);

        $jobArray = json_decode($jobList->body, true);

        $this->assertTrue(in_array($jobId, $jobArray));

        $jobResult = Async::fetchJobResult($this->client, $responseObject->headers['x-arango-async-id'][0]);

        $this->assertTrue($jobResult->headers['x-arango-async-id'] == $responseObject->headers['x-arango-async-id']);
        $this->assertArrayHasKey('x-arango-async-id', $jobResult->headers);

        $responseObject = Document::get($this->client, $collectionName . '/1', $requestBody);

        $responseBody    = $responseObject->body;
        $decodedJsonBody = json_decode($responseBody, true);
        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);
    }


    /**
     *
     */
    public function tearDown()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        Collection::delete($this->client, $collectionName);
    }
}
