<?php
/**
 *
 * File: CoreTest.php
 *
 * @package
 * @author Frank Mayer
 */
namespace frankmayer\ArangoDbPhpCore;

use frankmayer\ArangoDbPhpCore\Api\Rest\Async;
use frankmayer\ArangoDbPhpCore\Api\Rest\Batch;
use frankmayer\ArangoDbPhpCore\Api\Rest\Document;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse;
use phpDocumentor\Reflection\DocBlock\Tag;

require_once 'ArangoDbPhpCoreUnitTestCase.php';


/**
 * Class CoreTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class AsyncApiUnitTest extends ArangoDbPhpCoreUnitTestCase
{
    private $connector;
    private $batch;
    private $client;


    public function setup()
    {
        $this->connector = $this->getMockBuilder('TestConnector')
            ->getMock();

        $this->client = new Client($this->connector, getClientOptions());

        $this->batch = new Batch($this->client);

        $this->collectionNames[0] = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection-01';
        $this->collectionNames[1] = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection-02';
        $this->collectionNames[2] = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection-03';
    }


    /**
     *
     */
    public function testCreateDocumentAsync()
    {
        $createResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8\r\n\r\n[
  "270459873"
]
TAG;
        $body           = '{ "Hello": "World" }';

        $options = ['async' => true];
        $this->connector->method('request')
            ->willReturn($createResponse);

        $object = new Document($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->create($this->collectionNames[0], $body);

        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse', $response);
        $this->assertEquals(200, $response->status);
    }

    /**
     *
     */
    public function testFetchJobResult()
    {
        $createCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8
X-Arango-Async-Id: 265413601\r\n\r\n{"server":"arango","version":"2.1.0"}
TAG;
        $options                  = ['waitForSync' => true];
        $handle                   = 'products/1234567890';

        $this->connector->method('request')
            ->willReturn($createCollectionResponse);

        $object = new Async($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->fetchJobResult($handle, $options);

        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse', $response);
        $this->assertEquals(200, $response->status);
    }

    /**
     *
     */
    public function testDeleteJobResult()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8\r\n\r\n{
  "result" : true
}
TAG;
        $options                  = ['waitForSync' => true];
        $this->connector->method('request')
            ->willReturn($deleteCollectionResponse);

        $object = new Async($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->deleteJobResult('all');

        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse', $response);
        $this->assertEquals(200, $response->status);
    }

    /**
     *
     */
    public function testListJobResults()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8\r\n\r\n[
  "270459873"
]
TAG;
        $options                  = ['waitForSync' => true];
        $this->connector->method('request')
            ->willReturn($deleteCollectionResponse);

        $object = new Async($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->listJobResults('done');

        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse', $response);
        $this->assertEquals(200, $response->status);
    }
}
