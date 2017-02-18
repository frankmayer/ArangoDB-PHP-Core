<?php
/**
 *
 * File: HttpRequestTest.php
 *
 * @package
 * @author Frank Mayer
 */
namespace frankmayer\ArangoDbPhpCore\Tests\Unit;

use frankmayer\ArangoDbPhpCore\Api\Rest\Batch;
use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequest;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse;
use phpDocumentor\Reflection\DocBlock\Tag;

require_once __DIR__ . '/TestCase.php';


/**
 * Class CoreTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class HttpRequestTest extends TestCase
{
    protected $connector;
    protected $headers;
    protected $body;
    protected $batch;
    protected $collectionNames;


    public function setup()
    {
        $this->batchResponseBody = <<<TAG
HTTP/1.1 200 OK
connection: Keep-Alive
content-type: multipart/form-data; boundary=XXXbXXX
content-length: 1055\r\n\r\n
--XXXbXXX
Content-Type: application/x-arango-batchpart
Content-Id: 1\r\n\r\n
HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "9514299"
content-length: 53\r\n\r\n
{"error":false,"_id":"xyz/9514299","_key":"9514299","_rev":"9514299"}
--XXXbXXX
Content-Type: application/x-arango-batchpart
Content-Id: 2\r\n\r\n
HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "9579835"
content-length: 53\r\n\r\n
{"error":false,"_id":"xyz/9579835","_key":"9579835","_rev":"9579835"}
--XXXbXXX
Content-Type: application/x-arango-batchpart
Content-Id: 3\r\n\r\n
HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "9645371"
content-length: 53\r\n\r\n
{"error":false,"_id":"xyz/9645371","_key":"9645371","_rev":"9645371"}
--XXXbXXX--
TAG;


        $this->connector = $this->getMockBuilder(\TestConnector::class)
            ->getMock();
        $this->connector->method('request')
            ->willReturn($this->batchResponseBody);

        $this->setupProperties();

        $this->batch = new Batch($this->client);

        $this->headers = <<<TAG
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8
location: /_db/_system/_api/collection/products/properties\r\n\r\n
TAG;

        $this->body = '{
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
}';

        $this->batch = <<<TAG
HTTP/1.1 200 OK
connection: Keep-Alive
content-type: multipart/form-data; boundary=XXXbXXX
content-length: 1055\r\n\r\n
--XXXbXXX
Content-Type: application/x-arango-batchpart
Content-Id: 1\r\n\r\n
HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "9514299"
content-length: 53\r\n\r\n
{"error":false,"_id":"xyz/9514299","_key":"9514299","_rev":"9514299"}
--XXXbXXX
Content-Type: application/x-arango-batchpart
Content-Id: 2\r\n\r\n
HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "9579835"
content-length: 53\r\n\r\n
{"error":false,"_id":"xyz/9579835","_key":"9579835","_rev":"9579835"}
--XXXbXXX
Content-Type: application/x-arango-batchpart
Content-Id: 3\r\n\r\n
HTTP/1.1 202 Accepted
content-type: application/json; charset=utf-8
etag: "9645371"
content-length: 53\r\n\r\n
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
    public function testSettersGetters()
    {
        $request = new HttpRequest($this->client);
        $headers = $this->headers;
        $body    = $this->body;


        $response = $headers . $body;

        $testRequestObject = $request->setResponse($response);
        static::assertEquals($request, $testRequestObject);

        $testResponseString = $request->getResponse();
        static::assertEquals($response, $testResponseString);


        $address = '127.0.0.1:8529';

        $testRequestObject = $request->setAddress('127.0.0.1:8529');
        static::assertEquals($request, $testRequestObject);

        $testAddress = $request->getAddress();
        static::assertEquals($address, $testAddress);


        $body = '{}';

        $testRequestObject = $request->setBody($body);
        static::assertEquals($request, $testRequestObject);

        $testBody = $request->getBody();
        static::assertEquals($body, $testBody);


        $client = $this->client;

        $testRequestObject = $request->setClient($client);
        static::assertEquals($request, $testRequestObject);

        $testClientObject = $request->getClient();
        static::assertEquals($client, $testClientObject);


        $connector = $this->connector;

        $testRequestObject = $request->setConnector($connector);
        static::assertEquals($request, $testRequestObject);

        $testConnectorObject = $request->getConnector();
        static::assertEquals($connector, $testConnectorObject);


        $headers = ['content-type' => 'application/json; charset=utf-8'];

        $testRequestObject = $request->setHeaders($headers);
        static::assertEquals($request, $testRequestObject);

        $testHeaders = $request->getHeaders();
        static::assertEquals($headers, $testHeaders);


        $method = 'GET';

        $testRequestObject = $request->setMethod($method);
        static::assertEquals($request, $testRequestObject);

        $testMethod = $request->getMethod();
        static::assertEquals($method, $testMethod);


        $options = ['testOption' => 'testValue'];

        $testRequestObject = $request->setOptions($options);
        static::assertEquals($request, $testRequestObject);

        $testOptions = $request->getOptions();
        static::assertEquals($options, $testOptions);


        $path = '/testpath';

        $testRequestObject = $request->setPath($path);
        static::assertEquals($request, $testRequestObject);

        $testPath = $request->getPath();
        static::assertEquals($path, $testPath);
    }


    /**
     *
     */
    public function testBuildBatchResponseAndSplitHeadersAndBodies()
    {
        // Stop here and mark this test as incomplete.
        static::markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $collectionOptions = ['waitForSync' => true];

        $batchRequests = [];

        foreach ($this->collectionNames as $collectionName) {
            $collection = new Collection($this->client);

            /** @var $responseObject HttpResponse */
            $batchRequest = $collection->create($collectionName, $collectionOptions, ['isBatchPart' => true]);

            //            static::assertEquals(202, $batchRequest->status);
            $batchRequests[] = $batchRequest;
        }
        $this->batch   = new Batch($this->client);
        $batchResponse = $this->batch->send($this->client, $batchRequests);

        // check that contentId property return correct value
        foreach ($batchResponse->batch as $key => $batchPartResponseObject) {
            static::assertEquals($key + 1, $batchPartResponseObject->batchContentId);
        }
    }
}
