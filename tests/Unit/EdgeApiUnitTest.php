<?php
/**
 *
 * File: CoreTest.php
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */
namespace frankmayer\ArangoDbPhpCore;

use frankmayer\ArangoDbPhpCore\Api\Rest\Batch;
use frankmayer\ArangoDbPhpCore\Api\Rest\Edge;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse;

require_once __DIR__ . '/ArangoDbPhpCoreUnitTestCase.php';


/**
 * Class CoreTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class EdgeApiUnitTest extends ArangoDbPhpCoreUnitTestCase
{
    private $connector;
    private $batch;
    private $client;
    private $collectionNames;


    public function setup()
    {
        $this->connector = $this->getMockBuilder(\TestConnector::class)
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
    public function testIfHttpResponseInstantiable()
    {
        $response = new HttpResponse();
        $this->assertInstanceOf(HttpResponse::class, $response);
    }


    /**
     *
     */
    public function testCreateEdge()
    {
        $createResponse = <<<TAG
HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "1510938448"
location: /_db/_system/_api/document/edges/1510938448\r\n\r\n{
  "error" : false,
  "_id" : "edges/1510938448",
  "_rev" : "1510938448",
  "_key" : "1510938448"
}
TAG;
        $body           = '{ "Hello": "World" }';

        $this->connector->method('request')
            ->willReturn($createResponse);

        $object = new Edge($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->create('edges', $body, 'vertices/1', 'vertices/2');

        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertEquals(202, $response->status);
    }

    /**
     *
     */
    public function testDeleteEdge()
    {
        $deleteResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8\r\n\r\n{
  "error" : false,
  "_id" : "edges/1234567890",
  "_rev" : "1234567890",
  "_key" : "1234567890"
}
TAG;
        $handle         = 'edges/1234567890';

        $options = ['waitForSync' => true];
        $this->connector->method('request')
            ->willReturn($deleteResponse);

        $object = new Edge($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->delete($handle, $options);

        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertEquals(200, $response->status);
    }

    /**
     *
     */
    public function testGetEdge()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8
etag: "1493440336"\r\n\r\n{
  "hello" : "world",
  "_id" : "edges/1234567890",
  "_rev" : "1234567890",
  "_key" : "1234567890"
}
TAG;
        $handle                   = 'edges/1234567890';

        $options = ['waitForSync' => true];
        $this->connector->method('request')
            ->willReturn($deleteCollectionResponse);

        $object = new Edge($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->get($handle, $options);

        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertEquals(200, $response->status);
    }

    /**
     *
     */
    public function testGetEdgeHeader()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8
etag: "1234567890"\r\n\r\n
TAG;
        $handle                   = 'edges/1234567890';

        $options = ['waitForSync' => true];
        $this->connector->method('request')
            ->willReturn($deleteCollectionResponse);

        $object = new Edge($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->get($handle, $options);

        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertEquals(200, $response->status);
    }

    /**
     *
     */
    public function testPatchEdge()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "1506154320"
location: /_db/_system/_api/edge/products/1505171280\r\n\r\n{
  "error" : false,
  "_id" : "edges/1234567890",
  "_rev" : "1234567890",
  "_key" : "1234567890"
}
TAG;
        $handle                   = 'edges/1234567890';
        $body                     = '{ "Hello": "World" }';

        $options = ['waitForSync' => true];
        $this->connector->method('request')
            ->willReturn($deleteCollectionResponse);

        $object = new Edge($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->update($handle, $body, $options);

        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertEquals(202, $response->status);
    }

    /**
     *
     */
    public function testReplaceEdge()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "1498093392"
location: /_db/_system/_api/edge/products/1497765712\r\n\r\n{
  "error" : false,
  "_id" : "edges/1497765712",
  "_rev" : "1498093392",
  "_key" : "1497765712"
}
TAG;
        $handle                   = 'edges/1234567890';
        $body                     = '{ "Hello": "World" }';

        $this->connector->method('request')
            ->willReturn($deleteCollectionResponse);

        $object = new Edge($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->replace($handle, $body);

        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertEquals(202, $response->status);
    }

    /**
     *
     */
    public function testGetAllEdges()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8\r\n\r\n{
  "edges" : [
    "/_api/edge/products/1495340880",
    "/_api/edge/products/1494685520",
    "/_api/edge/products/1495013200"
  ]
}
TAG;
        $options                  = ['excludeSystem' => true];
        $this->connector->method('request')
            ->willReturn($deleteCollectionResponse);

        $object = new Edge($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->getAll('edges', $options);

        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertEquals(200, $response->status);
    }
}
