<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Batch Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140 as ArangoDbApi;

use frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequestInterface;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponseInterface;


class IocTest extends
    \PHPUnit_Framework_TestCase
{
    public $client;
    public $collectionNames;

    /**
     * @var HttpRequest|HttpRequestInterface
     */
    public $request;

    /**
     * @var HttpResponse|HttpResponseInterface
     */
    public $response;


    public function setUp()
    {
        $connector    = new CurlHttpConnector();
        $this->client = $this->client = getClient($connector);
    }


    public function testBindAndMakeHttpRequest()
    {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            // This is the way to bind an HttpRequest in PHP 5.4+

            Client::bind(
                  'httpRequest',
                      function () {
                          $instance         = new HttpRequest();
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
                          $instance         = new HttpRequest();
                          $instance->client = $me->client;

                          return $instance;
                      }
            );
        }
        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $this->request = Client::make('httpRequest');
        $this->assertInstanceOf('frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequestInterface', $this->request);
    }


    public function testBindAndMakeHttpResponsePlusGettersSetters()
    {
        $this->request         = Client::make('httpRequest');
        $this->request->path   = '/_admin/version';
        $this->request->method = HttpRequest::METHOD_GET;
        $this->request->request();

        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            // This is the way to bind an HttpRequest in PHP 5.4+

            Client::bind(
                  'httpResponse',
                      function () {
                          $response = new HttpResponse();

                          $response->request = $this->request;

                          //                          $response->doConstruct();

                          return $response;
                      }
            );
        } else {
            // This is the way to bind an HttpRequest in PHP 5.3.x

            $me = $this;
            Client::bind(
                  'httpResponse',
                      function () use ($me) {
                          $response = new HttpResponse();

                          $response->request = $me->request;

                          //                          $response->doConstruct();

                          return $response;
                      }
            );
        }
        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $this->response = Client::make('httpResponse');
        $this->response->doConstruct();

        //        echo get_class($this->request);
        $this->assertInstanceOf('frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse', $this->response);
        $decodedBody = json_decode($this->response->body, true);
        $this->assertTrue($decodedBody['server'] === 'arango');
        $this->assertAttributeEmpty('protocol', $this->response);


        // test verboseExtractStatusLine
        $this->response                           = Client::make('httpResponse');
        $this->response->verboseExtractStatusLine = true;
        $this->response->doConstruct();
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
        $this->assertInternalType('object',$testValue);

        $this->response->setRequest($testValue);

        $testValue = $this->response->getRequest();
        $this->assertInternalType('object',$testValue);

        
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
