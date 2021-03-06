<?php
/**
 *
 * File: CoreTest.php
 *
 * @package
 * @author Frank Mayer
 */
namespace frankmayer\ArangoDbPhpCore\Tests\Unit;

use frankmayer\ArangoDbPhpCore\Api\Rest\Async;
use frankmayer\ArangoDbPhpCore\Api\Rest\Batch;
use frankmayer\ArangoDbPhpCore\Api\Rest\Document;
use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse;
use phpDocumentor\Reflection\DocBlock\Tag;


/**
 * Class CoreTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class AsyncApiTest extends TestCase
{
    protected $connector;
    protected $batch;


    public function setup()
    {

        $this->connector = $this->getMockBuilder(\TestConnector::class)
            ->getMock();

        $this->setupProperties();

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
        $this->connector->method('send')
            ->willReturn($createResponse);

        $object = new Document($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->create($this->collectionNames[0], $body);

        static::assertInstanceOf(HttpResponse::class, $response);
        static::assertEquals(200, $response->status);
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

        $this->connector->method('send')
            ->willReturn($createCollectionResponse);

        $object = new Async($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->fetchJobResult($handle, $options);

        static::assertInstanceOf(HttpResponse::class, $response);
        static::assertEquals(200, $response->status);
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
        $this->connector->method('send')
            ->willReturn($deleteCollectionResponse);

        $object = new Async($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->deleteJobResult('all');

        static::assertInstanceOf(HttpResponse::class, $response);
        static::assertEquals(200, $response->status);
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
        $this->connector->method('send')
            ->willReturn($deleteCollectionResponse);

        $object = new Async($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->listJobResults('done');

        static::assertInstanceOf(HttpResponse::class, $response);
        static::assertEquals(200, $response->status);
    }
}
