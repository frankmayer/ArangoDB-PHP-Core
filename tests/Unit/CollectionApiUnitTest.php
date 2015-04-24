<?php
/**
 *
 * File: CoreTest.php
 *
 * @package
 * @author Frank Mayer
 */
namespace frankmayer\ArangoDbPhpCore;

use frankmayer\ArangoDbPhpCore\Api\Rest\Batch;
use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse;
use phpDocumentor\Reflection\DocBlock\Tag;

require_once('ArangoDbPhpCoreUnitTestCase.php');


/**
 * Class CoreTest
 * @package frankmayer\ArangoDbPhpCore
 */
class CollectionApiUnitTest extends ArangoDbPhpCoreUnitTestCase
{
    private $connector;
    private $batch;
    private $client;


    public function setup()
    {
        $this->connector = $this->getMockBuilder('TestConnector')
                                ->getMock();

        $this->client = new Client($this->connector, getClientOptions());

        //        $this->client = $this->getMockBuilder('frankmayer\ArangoDbPhpCore\Client')
        //                             ->setConstructorArgs([$this->connector, getClientOptions()])
        //                             ->getMock();

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
        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse', $response);
    }


    /**
     *
     */
    public function testCreateCollection()
    {
        $createCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8
location: /_db/_system/_api/collection/testCollectionBasics\r\n\r\n{
  "id" : "1372591952",
  "name" : "testCollectionBasics",
  "waitForSync" : false,
  "isVolatile" : false,
  "isSystem" : false,
  "status" : 3,
  "type" : 2,
  "error" : false,
  "code" : 200
}
TAG;
        $options        = ["waitForSync" => true];
        $this->connector->method('request')
                        ->willReturn($createCollectionResponse);

        $object = new Collection($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->create($this->collectionNames[0], $options);

        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse', $response);
        $this->assertEquals(200, $response->status);
    }

    /**
     *
     */
    public function testDeleteCollection()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8\r\n\r\n{
  "id" : "1398937424",
  "error" : false,
  "code" : 200
}
TAG;
        $options        = ["waitForSync" => true];
        $this->connector->method('request')
                        ->willReturn($deleteCollectionResponse);

        $object = new Collection($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->drop($this->collectionNames[0], $options);

        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse', $response);
        $this->assertEquals(200, $response->status);
    }

    /**
     *
     */
    public function testTruncateCollection()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8\r\n\r\n{
  "id" : "1397167952",
  "name" : "products",
  "isSystem" : false,
  "status" : 3,
  "type" : 2,
  "error" : false,
  "code" : 200
}
TAG;
        $options        = ["waitForSync" => true];
        $this->connector->method('request')
                        ->willReturn($deleteCollectionResponse);

        $object = new Collection($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->truncate($this->collectionNames[0], $options);

        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse', $response);
        $this->assertEquals(200, $response->status);
    }

    /**
     *
     */
    public function testGetAllCollection()
    {
        $deleteCollectionResponse = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8\r\n\r\n{
  "collections" : [
    {
      "id" : "5707600",
      "name" : "_configuration",
      "isSystem" : true,
      "status" : 3,
      "type" : 2
    },
    {
      "id" : "1001854800",
      "name" : "Groceries",
      "isSystem" : false,
      "status" : 3,
      "type" : 2
    }
}
TAG;
        $options                  = ["excludeSystem" => true];
        $this->connector->method('request')
                        ->willReturn($deleteCollectionResponse);

        $object = new Collection($this->client);

        /** @var $responseObject HttpResponse */
        $response = $object->getAll($options);

        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse', $response);
        $this->assertEquals(200, $response->status);
    }
}