<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Async Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


use frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector;
use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140 as ArangoDbApi;
use frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse;


/**
 * Class AsyncTest
 * @package frankmayer\ArangoDbPhpCore
 */
class AsyncTest extends
    \PHPUnit_Framework_TestCase
{
    /**
     * @var
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

        $collection         = new ArangoDbApi\Collection();
        $collection->client = $this->client;

        $responseObject = $collection->create($collectionName, $collectionOptions);
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $requestBody      = ['name' => 'frank', '_key' => '1'];
        $document         = new ArangoDbApi\Document();
        $document->client = $this->client;


        $responseObject = $document->create($collectionName, $requestBody, null, ['async' => true]);

        /** @var $responseObject HttpResponse */
        $this->assertEquals(202, $responseObject->status);

        sleep(1);

        $document         = new ArangoDbApi\Document();
        $document->client = $this->client;

        $responseObject = $document->get($collectionName . '/1', $requestBody);

        $responseBody    = $responseObject->body;
        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);
    }

    /**
     *
     */
    public function testCreateCollectionAndStoredAsyncDocumentCreation()
    {

        $job               = new ArangoDbApi\Async();
        $job->client       = $this->client;
        $jobDeleteResponse = $job->deleteJobResult('all');

        // todo 1 Frank Write real test for deleting job results with stamp
        $jobDeleteResponse = $job->deleteJobResult('all', time());


        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions = ["waitForSync" => true];

        $collection         = new ArangoDbApi\Collection();
        $collection->client = $this->client;

        $responseObject = $collection->create($collectionName, $collectionOptions);
        $body           = $responseObject->body;

        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $requestBody      = ['name' => 'frank', '_key' => '1'];
        $document         = new ArangoDbApi\Document();
        $document->client = $this->client;

        $responseObject = $document->create($collectionName, $requestBody, null, ['async' => 'store']);

        /** @var $responseObject HttpResponse */
        $this->assertEquals(202, $responseObject->status);

        sleep(1);

        $jobId    = $responseObject->headers['x-arango-async-id'];
        $jobList  = $job->listJobResults('done', 1);
        $jobArray = json_decode($jobList->body, true);

        $this->assertTrue(in_array($jobId, $jobArray));


        $jobResult = $job->fetchJobResult($responseObject->headers['x-arango-async-id']);
        $this->assertTrue($jobResult->headers['x-arango-async-id'] == $responseObject->headers['x-arango-async-id']);
        $this->assertArrayHasKey('x-arango-async-id', $jobResult->headers);


        $document         = new ArangoDbApi\Document();
        $document->client = $this->client;

        $responseObject = $document->get($collectionName . '/1', $requestBody);

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


        $collection         = new ArangoDbApi\Collection();
        $collection->client = $this->client;

        $collection->delete($collectionName);
    }
}
