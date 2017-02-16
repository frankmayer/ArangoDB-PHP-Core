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
        $this->connector = new Connector();

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
        static::markTestSkipped(
            'The functionality needs to be implemented in the API first (IOC?).'
        );

        $this->client->bind(
            'Request',
            function () {
                return $this->client->getRequest();
            }
        );

        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $this->request = $this->client->make('Request');
        static::assertInstanceOf(AbstractHttpRequest::class, $this->request);


        $testValue = $this->request->getAddress();
        static::assertNull($testValue);

        $this->request->setAddress('testAddress');

        $testValue = $this->request->getAddress();
        static::assertEquals('testAddress', $testValue);


        $testValue = $this->request->getBody();
        static::assertNull($testValue);

        $this->request->setBody('testBody');

        $testValue = $this->request->getBody();
        static::assertEquals('testBody', $testValue);


        $testValue1 = $this->request->getClient();
        static::assertInstanceOf(Client::class, $testValue1);

        $this->request->setClient($this->client);

        $testValue1 = $this->request->getClient();
        static::assertEquals($this->client, $testValue1);


        $testValue1 = $this->request->getConnector();
        static::assertNull($testValue1);

        $this->request->setConnector($this->connector);

        $testValue1 = $this->request->getConnector();
        static::assertEquals($this->connector, $testValue1);


        $testValue = $this->request->getHeaders();
        static::assertEmpty($testValue);

        $this->request->setHeaders('testHeaders');

        $testValue = $this->request->getHeaders();
        static::assertEquals('testHeaders', $testValue);


        $testValue = $this->request->getMethod();
        static::assertNull($testValue);

        $this->request->setMethod('testMethod');

        $testValue = $this->request->getMethod();
        static::assertEquals('testMethod', $testValue);


        $testValue1 = $this->request->getOptions();
        static::assertEmpty($testValue1);

        $this->request->setOptions(['testOption' => 'testVal']);

        $testValue = $this->request->getOptions();
        static::assertArrayHasKey('testOption', $testValue);

        $this->request->setOptions($testValue1);

        $testValue = $this->request->getOptions();
        static::assertEquals($testValue1, $testValue);


        $testValue = $this->request->getPath();
        static::assertNull($testValue);

        $this->request->setPath('testPath');

        $testValue = $this->request->getPath();
        static::assertEquals('testPath', $testValue);


        $testValue = $this->request->getResponse();
        static::assertNull($testValue);

        $this->request->setResponse('testResponse');

        $testValue = $this->request->getResponse();
        static::assertEquals('testResponse', $testValue);
    }


    /**
     * @throws ClientException
     */
    public function testBindAndMakeHttpResponsePlusGettersSetters()
    {
        static::markTestSkipped(
            'The functionality needs to be implemented in the API first (IOC?).'
        );
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
        static::assertInstanceOf(HttpResponseInterface::class, $this->response);
        $decodedBody = json_decode($this->response->body, true);
        static::assertSame($decodedBody['server'], 'arango');
        static::assertAttributeEmpty('protocol', $this->response);


        // test verboseExtractStatusLine
        $this->response                           = $this->client->make('Response');
        $this->response->verboseExtractStatusLine = true;
        $this->response->build($this->request);
        static::assertAttributeNotEmpty('protocol', $this->response);


        $testValue = $this->response->getBatch();
        static::assertEmpty($testValue);

        $this->response->setBatch(true);

        $testValue = $this->response->getBatch();
        static::assertTrue($testValue);


        $testValue = $this->response->getBody();
        static::assertNotEmpty($testValue);

        $this->response->setBody('testBody');

        $testValue = $this->response->getBody();
        static::assertEquals('testBody', $testValue);


        $testValue = $this->response->getHeaders();
        static::assertNotEmpty($testValue);

        $this->response->setHeaders('testHeaders');

        $testValue = $this->response->getHeaders();
        static::assertEquals('testHeaders', $testValue);


        $testValue = $this->response->getRequest();
        static::assertInternalType('object', $testValue);

        $this->response->setRequest($testValue);

        $testValue = $this->response->getRequest();
        static::assertInternalType('object', $testValue);


        $testValue = $this->response->getStatus();
        static::assertNotEmpty($testValue);

        $this->response->setStatus(202);

        $testValue = $this->response->getStatus();
        static::assertEquals(202, $testValue);


        $testValue = $this->response->getProtocol();
        static::assertEquals('HTTP/1.1', $testValue);


        $testValue = $this->response->getStatusPhrase();
        static::assertEquals('OK', $testValue);


        $testValue = $this->response->getVerboseExtractStatusLine();
        static::assertEquals(true, $testValue);

        $this->response->setVerboseExtractStatusLine(false);

        $testValue = $this->response->getVerboseExtractStatusLine();
        static::assertEquals(false, $testValue);
    }
}
