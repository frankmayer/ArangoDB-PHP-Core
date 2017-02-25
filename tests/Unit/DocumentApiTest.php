<?php
/**
 *
 * File: CoreTest.php
 *
 * @package
 * @author Frank Mayer
 */
namespace frankmayer\ArangoDbPhpCore\Tests\Unit;

use frankmayer\ArangoDbPhpCore\Api\Rest\Batch;
use frankmayer\ArangoDbPhpCore\Api\Rest\Document;
use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse;
use phpDocumentor\Reflection\DocBlock\Tag;

require_once __DIR__ . '/TestCase.php';


/**
 * Class CoreTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class DocumentApiTest extends TestCase
{
    protected $connector;
    protected $batch;
    protected $collectionNames;


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
    public function testIfHttpResponseInstantiable()
    {
        $response = new HttpResponse();
        static::assertInstanceOf(HttpResponse::class, $response);
    }


    /**
     *
     */
    public function testCreateDocument()
    {
        $createResponse = <<<TAG
HTTP/1.1 201 Created
content-type: application/json; charset=utf-8
etag: "1491343184"
location: /_db/_system/_api/document/products/1491343184\r\n\r\n{
  "error" : false,
  "_id" : "products/1491343184",
  "_rev" : "1491343184",
  "_key" : "1491343184"
}
TAG;
        $body           = '{ "Hello": "World" }';

        $options = ['waitForSync' => true];
        $this->connector->method('send')
            ->willReturn($createResponse);

        $object = new Document($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->create($this->collectionNames[0], $body);

        static::assertInstanceOf(HttpResponse::class, $response);
        static::assertEquals(201, $response->status);
    }

    /**
     *
     */
    public function testDeleteDocument()
    {
        $deleteResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8\r\n\r\n{
  "error" : false,
  "_id" : "products/1506744144",
  "_rev" : "1506744144",
  "_key" : "1506744144"
}
TAG;
        $handle         = 'products/1234567890';

        $options = ['waitForSync' => true];
        $this->connector->method('send')
            ->willReturn($deleteResponse);

        $object = new Document($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->delete($handle, $options);

        static::assertInstanceOf(HttpResponse::class, $response);
        static::assertEquals(200, $response->status);
    }

    /**
     *
     */
    public function testGetDocument()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8
etag: "1493440336"\r\n\r\n{
  "hello" : "world",
  "_id" : "products/1493440336",
  "_rev" : "1493440336",
  "_key" : "1493440336"
}
TAG;
        $handle                   = 'products/1234567890';

        $options = ['waitForSync' => true];
        $this->connector->method('send')
            ->willReturn($deleteCollectionResponse);

        $object = new Document($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->get($handle, $options);

        static::assertInstanceOf(HttpResponse::class, $response);
        static::assertEquals(200, $response->status);
    }

    /**
     *
     */
    public function testGetDocumentHeader()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8
etag: "1493440336"\r\n\r\n
TAG;
        $handle                   = 'products/1234567890';

        $options = ['waitForSync' => true];
        $this->connector->method('send')
            ->willReturn($deleteCollectionResponse);

        $object = new Document($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->get($handle, $options);

        static::assertInstanceOf(HttpResponse::class, $response);
        static::assertEquals(200, $response->status);
    }

    /**
     *
     */
    public function testPatchDocument()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "1506154320"
location: /_db/_system/_api/document/products/1505171280\r\n\r\n{
  "error" : false,
  "_id" : "products/1505171280",
  "_rev" : "1506154320",
  "_key" : "1505171280"
}
TAG;
        $handle                   = 'products/1234567890';
        $body                     = '{ "Hello": "World" }';

        $options = ['waitForSync' => true];
        $this->connector->method('send')
            ->willReturn($deleteCollectionResponse);

        $object = new Document($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->update($handle, $body, $options);

        static::assertInstanceOf(HttpResponse::class, $response);
        static::assertEquals(202, $response->status);
    }

    /**
     *
     */
    public function testReplaceDocument()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "1498093392"
location: /_db/_system/_api/document/products/1497765712\r\n\r\n{
  "error" : false,
  "_id" : "products/1497765712",
  "_rev" : "1498093392",
  "_key" : "1497765712"
}
TAG;
        $handle                   = 'products/1234567890';
        $body                     = '{ "Hello": "World" }';

        $options = ['waitForSync' => true];
        $this->connector->method('send')
            ->willReturn($deleteCollectionResponse);

        $object = new Document($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->replace($handle, $body);

        static::assertInstanceOf(HttpResponse::class, $response);
        static::assertEquals(202, $response->status);
    }

    /**
     *
     */
    public function testGetAllDocuments()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8\r\n\r\n{
  "documents" : [
    "/_api/document/products/1495340880",
    "/_api/document/products/1494685520",
    "/_api/document/products/1495013200"
  ]
}
TAG;
        $options                  = ['excludeSystem' => true];
        $this->connector->method('send')
            ->willReturn($deleteCollectionResponse);

        $object = new Document($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->getAll($this->collectionNames[0], $options);

        static::assertInstanceOf(HttpResponse::class, $response);
        static::assertEquals(200, $response->status);
    }
}
