<?php

/**
 * ArangoDB PHP Core Client Integration Test-Suite: Batch Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Integration;

require_once __DIR__ . '/TestCase.php';

use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\ClientException;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequest;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequestInterface;
use frankmayer\ArangoDbPhpCore\Protocols\ResponseInterface;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponseInterface;
use frankmayer\ArangoDbPhpCore\Protocols\Http\AbstractHttpRequest;


/**
 * Class IocTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class IocTest extends TestCase
{
    /**
     * @var Client
     */
    public $client;
    /**
     * @var
     */
    public $collectionNames;

    /**
     * @var HttpRequestInterface
     */
    public $request;

    /**
     * @var ResponseInterface
     */
    public $response;
    /**
     * @var
     */
    public $connector;


    /**
     *
     */
    public function setUp()
    {
        $this->connector    = new Connector();

        $this->setupProperties();

        $this->client->bind(
            'Request',
            function () {
                return $this->client->getRequest();
            }
        );
    }


    /**
     * @throws ClientException
     */
    public function testBindAndMakeHttpRequest()
    {
        $this->client->bind(
            'Request',
            function () {
                return $this->client->getRequest();
            }
        );

        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $this->request = $this->client->make('Request');
        $this->assertInstanceOf(AbstractHttpRequest::class, $this->request);


        $testValue = $this->request->getAddress();
        $this->assertNull($testValue);

        $this->request->setAddress('testAddress');

        $testValue = $this->request->getAddress();
        $this->assertEquals('testAddress', $testValue);


        $testValue = $this->request->getBody();
        $this->assertNull($testValue);

        $this->request->setBody('testBody');

        $testValue = $this->request->getBody();
        $this->assertEquals('testBody', $testValue);


        $testValue1 = $this->request->getClient();
        $this->assertInstanceOf(Client::class, $testValue1);

        $this->request->setClient($this->client);

        $testValue1 = $this->request->getClient();
        $this->assertEquals($this->client, $testValue1);


        $testValue1 = $this->request->getConnector();
        $this->assertNull($testValue1);

        $this->request->setConnector($this->connector);

        $testValue1 = $this->request->getConnector();
        $this->assertEquals($this->connector, $testValue1);


        $testValue = $this->request->getHeaders();
        $this->assertEmpty($testValue);

        $this->request->setHeaders('testHeaders');

        $testValue = $this->request->getHeaders();
        $this->assertEquals('testHeaders', $testValue);


        $testValue = $this->request->getMethod();
        $this->assertNull($testValue);

        $this->request->setMethod('testMethod');

        $testValue = $this->request->getMethod();
        $this->assertEquals('testMethod', $testValue);


        $testValue1 = $this->request->getOptions();
        $this->assertEmpty($testValue1);

        $this->request->setOptions(['testOption' => 'testVal']);

        $testValue = $this->request->getOptions();
        $this->assertArrayHasKey('testOption', $testValue);

        $this->request->setOptions($testValue1);

        $testValue = $this->request->getOptions();
        $this->assertEquals($testValue1, $testValue);


        $testValue = $this->request->getPath();
        $this->assertNull($testValue);

        $this->request->setPath('testPath');

        $testValue = $this->request->getPath();
        $this->assertEquals('testPath', $testValue);


        $testValue = $this->request->getResponse();
        $this->assertNull($testValue);

        $this->request->setResponse('testResponse');

        $testValue = $this->request->getResponse();
        $this->assertEquals('testResponse', $testValue);
    }


    /**
     * @throws ClientException
     */
    public function testBindAndMakeHttpResponsePlusGettersSetters()
    {
        $this->request         = $this->client->make('Request');
        $this->request->path   = '/_admin/version';
        $this->request->method = HttpRequest::METHOD_GET;
        $this->request->send();

        $this->client->bind(
            'Response',
            function () {
                return $this->client->getResponse();
            }
        );

        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $this->response = $this->client->make('Response');
        $this->response->build($this->request);

        //        echo get_class($this->request);
        $this->assertInstanceOf(HttpResponseInterface::class, $this->response);
        $decodedBody = json_decode($this->response->body, true);
        $this->assertSame($decodedBody['server'], 'arango');
        $this->assertAttributeEmpty('protocol', $this->response);


        // test verboseExtractStatusLine
        $this->response                           = $this->client->make('Response');
        $this->response->verboseExtractStatusLine = true;
        $this->response->build($this->request);
        $this->assertAttributeNotEmpty('protocol', $this->response);


        $testValue = $this->response->getBatch();
        $this->assertEmpty($testValue);

        $this->response->setBatch(true);

        $testValue = $this->response->getBatch();
        $this->assertTrue($testValue);


        $testValue = $this->response->getBody();
        $this->assertNotEmpty($testValue);

        $this->response->setBody('testBody');

        $testValue = $this->response->getBody();
        $this->assertEquals('testBody', $testValue);


        $testValue = $this->response->getHeaders();
        $this->assertNotEmpty($testValue);

        $this->response->setHeaders('testHeaders');

        $testValue = $this->response->getHeaders();
        $this->assertEquals('testHeaders', $testValue);


        $testValue = $this->response->getRequest();
        $this->assertInternalType('object', $testValue);

        $this->response->setRequest($testValue);

        $testValue = $this->response->getRequest();
        $this->assertInternalType('object', $testValue);


        $testValue = $this->response->getStatus();
        $this->assertNotEmpty($testValue);

        $this->response->setStatus(202);

        $testValue = $this->response->getStatus();
        $this->assertEquals(202, $testValue);


        $testValue = $this->response->getProtocol();
        $this->assertEquals('HTTP/1.1', $testValue);


        $testValue = $this->response->getStatusPhrase();
        $this->assertEquals('OK', $testValue);


        $testValue = $this->response->getVerboseExtractStatusLine();
        $this->assertEquals(true, $testValue);

        $this->response->setVerboseExtractStatusLine(false);

        $testValue = $this->response->getVerboseExtractStatusLine();
        $this->assertEquals(false, $testValue);
    }
}
