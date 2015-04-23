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
use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequest;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse;
use phpDocumentor\Reflection\DocBlock\Tag;

require_once('ArangoDbPhpCoreUnitTestCase.php');


/**
 * Class CoreTest
 * @package frankmayer\ArangoDbPhpCore
 */
class HttpResponseUnitTest extends ArangoDbPhpCoreUnitTestCase
{
    private $connector;
    private $headers;
    private $body;
    private $batch;
    private $client;
    private $collectionNames;


    /**
     *
     */
    public function testRequest()
    {
        //todo: write test
    }


    public function setup()
    {

        $this->batchResponseBody = <<<'TAG'
   HTTP/1.1 200 OK
connection: Keep-Alive
content-type: multipart/form-data; boundary=XXXbXXX
content-length: 1055

--XXXbXXX
Content-Type: application/x-arango-batchpart
Content-Id: 1

HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "9514299"
content-length: 53

{"error":false,"_id":"xyz/9514299","_key":"9514299","_rev":"9514299"}
--XXXbXXX
Content-Type: application/x-arango-batchpart
Content-Id: 2

HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "9579835"
content-length: 53

{"error":false,"_id":"xyz/9579835","_key":"9579835","_rev":"9579835"}
--XXXbXXX
Content-Type: application/x-arango-batchpart
Content-Id: 3

HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "9645371"
content-length: 53

{"error":false,"_id":"xyz/9645371","_key":"9645371","_rev":"9645371"}
--XXXbXXX--
TAG;


        $this->connector = $this->getMockBuilder('TestConnector')
                                ->getMock();
        $this->connector->method('request')
                        ->willReturn($this->batchResponseBody);


        $this->client = new Client($this->connector, getClientOptions());

        //        $this->client = $this->getMockBuilder('frankmayer\ArangoDbPhpCore\Client')
        //                             ->setConstructorArgs([$this->connector, getClientOptions()])
        //                             ->getMock();

        $this->batch = new Batch($this->client);

        $this->headers = <<<'TAG'
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8
location: /_db/_system/_api/collection/products/properties


TAG;

        $this->body = <<<'TAG'
{
  "id" : "1373247312",
  "name" : "products",
  "isSystem" : false,
  "doCompact" : true,
  "isVolatile" : false,
  "journalSize" : 1048576,
  "keyOptions" : {
    "type" : "traditional",
    "allowUserKeys" : true
  },
  "waitForSync" : true,
  "status" : 3,
  "type" : 2,
  "error" : false,
  "code" : 200
}
TAG;

        $this->batch = <<<TAG
HTTP/1.1 200 OK
connection: Keep-Alive
content-type: multipart/form-data; boundary=XXXbXXX
content-length: 1055

--XXXbXXX
Content-Type: application/x-arango-batchpart
Content-Id: 1

HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "9514299"
content-length: 53

{"error":false,"_id":"xyz/9514299","_key":"9514299","_rev":"9514299"}
--XXXbXXX
Content-Type: application/x-arango-batchpart
Content-Id: 2

HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "9579835"
content-length: 53

{"error":false,"_id":"xyz/9579835","_key":"9579835","_rev":"9579835"}
--XXXbXXX
Content-Type: application/x-arango-batchpart
Content-Id: 3

HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "9645371"
content-length: 53

{"error":false,"_id":"xyz/9645371","_key":"9645371","_rev":"9645371"}
--XXXbXXX--
TAG;


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
    public function testBuildSplitHeadersAndBody()
    {
        $request = new HttpRequest();
        $headers = $this->headers;
        $body    = $this->body;

        $request->response = $headers . $body;
        $response          = new HttpResponse();

        $response = $response->build($request);
        $this->assertEquals($response->getBody(), $body);
        $this->assertEquals($response->status, explode(" ", explode("\r\n", $headers)[0])[1]);
    }


    /**
     *
     */
    public function testBuildAndCheckForStatusAndBodyAttributesInResponse()
    {
        $request = new HttpRequest();
        $headers = $this->headers;
        $body    = $this->body;

        $request->response = $headers . $body;
        $response          = new HttpResponse();

        $response = $response->build($request);
        $this->assertEquals($response->getBody(), $body);
        $this->assertEquals($response->status, explode(" ", explode("\r\n", $headers)[0])[1]);
        $this->assertEquals(json_decode($body, true)['code'], json_decode($response->body, true)['code']);
        $this->assertEquals(json_decode($body, true)['name'], json_decode($response->body, true)['name']);
        $this->assertEquals(json_decode($body, true)['keyOptions']['type'],
            json_decode($response->body, true)['keyOptions']['type']);
    }

    /**
     *
     */
    public function testBuildWithVerboseExtractStatusLineEqualsTrue()
    {
        $request = new HttpRequest();
        $headers = $this->headers;
        $body    = $this->body;

        $request->response                  = $headers . $body;
        $response                           = new HttpResponse();
        $response->verboseExtractStatusLine = true;

        $response = $response->build($request);
        $this->assertEquals($response->getBody(), $body);
        $this->assertEquals($response->status, explode(" ", explode("\r\n", $headers)[0])[1]);
        $this->assertEquals(json_decode($body, true)['code'], json_decode($response->body, true)['code']);
        $this->assertEquals(json_decode($body, true)['name'], json_decode($response->body, true)['name']);
        $this->assertEquals(json_decode($body, true)['keyOptions']['type'],
            json_decode($response->body, true)['keyOptions']['type']);
    }


    /**
     *
     */
    public function testBuildBatchResponseAndSplitHeadersAndBodies()
    {
        $collectionOptions = ["waitForSync" => true];

        $batchRequests = [];

        foreach ($this->collectionNames as $collectionName) {
            $collection = new Collection($this->client);

            /** @var $responseObject HttpResponse */
            $batchRequest = $collection->create($collectionName, $collectionOptions, ['isBatchPart' => true]);

            //            $this->assertEquals(202, $batchRequest->status);
            $batchRequests[] = $batchRequest;
        }
        $this->batch   = new Batch($this->client);
        $batchResponse = $this->batch->send($this->client, $batchRequests);

        // check that contentId property return correct value
        foreach ($batchResponse->batch as $key => $batchPartResponseObject) {
            $this->assertEquals($key + 1, $batchPartResponseObject->batchContentId);
        }
    }
}