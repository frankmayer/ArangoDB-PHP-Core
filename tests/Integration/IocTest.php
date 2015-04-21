<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Batch Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;

require_once('ArangoDbPhpCoreIntegrationTestCase.php');

use frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector;
use frankmayer\ArangoDbPhpCore\Protocols\Http\Request;
use frankmayer\ArangoDbPhpCore\Protocols\Http\Response;


/**
 * Class IocTest
 * @package frankmayer\ArangoDbPhpCore
 */
class IocIntegrationTest extends ArangoDbPhpCoreIntegrationTestCase
{
    /**
     * @var
     */
    public $client;
    /**
     * @var
     */
    public $collectionNames;

    /**
     * @var RequestInterface
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
        $connector       = new Connector();
        $this->connector = $connector;

        $this->client = $this->client = getClient($connector);
    }


    /**
     * @throws ClientException
     */
    public function testBindAndMakeHttpRequest()
    {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            // This is the way to bind an HttpRequest in PHP 5.4+

            Client::bind(
                'httpRequest',
                function () {
                    $instance         = new Request();
                    $instance->client = $this->client;

                    return $instance;
                }
            );
        } else {
            // This is the way to bind an HttpRequest in PHP 5.3.x

            $me = $this;
            Client::bind(
                'httpRequest',
                function () use ($me) {
                    $instance         = new Request();
                    $instance->client = $me->client;

                    return $instance;
                }
            );
        }
        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $this->request = Client::make('httpRequest');
        $this->assertInstanceOf('frankmayer\ArangoDbPhpCore\Protocols\Http\RequestInterface', $this->request);


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
        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Client', $testValue1);

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
        $this->request         = Client::make('httpRequest');
        $this->request->path   = '/_admin/version';
        $this->request->method = Request::METHOD_GET;
        $this->request->send();

        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            // This is the way to bind an HttpRequest in PHP 5.4+

            Client::bind(
                'httpResponse',
                function () {
                    $response = new Response();

                    $response->request = $this->request;

                    return $response;
                }
            );
        } else {
            // This is the way to bind an HttpRequest in PHP 5.3.x

            $me = $this;
            Client::bind(
                'httpResponse',
                function () use ($me) {
                    $response = new Response();

                    $response->request = $me->request;

                    return $response;
                }
            );
        }

        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $this->response = Client::make('httpResponse');
        $this->response->build($this->request);

        //        echo get_class($this->request);
        $this->assertInstanceOf('frankmayer\ArangoDbPhpCore\Protocols\Http\Response', $this->response);
        $decodedBody = json_decode($this->response->body, true);
        $this->assertTrue($decodedBody['server'] === 'arango');
        $this->assertAttributeEmpty('protocol', $this->response);


        // test verboseExtractStatusLine
        $this->response                           = Client::make('httpResponse');
        $this->response->verboseExtractStatusLine = true;
        $this->response->build($this->request);
        $this->assertAttributeNotEmpty('protocol', $this->response);

        $testValue = $this->response->getAsync();
        $this->assertEmpty($testValue);

        $this->response->setAsync(true);

        $testValue = $this->response->getAsync();
        $this->assertTrue($testValue);


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