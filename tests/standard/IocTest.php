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
                          return new HttpRequest($this->client);
                      }
            );
        } else {
            // This is the way to bind an HttpRequest in PHP 5.3.x

            $me = $this;
            Client::bind(
                  'httpRequest',
                      function () use ($me) {
                          return new HttpRequest($me->client);
                      }
            );
        }
        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $this->request = Client::make('httpRequest');
        //        echo get_class($this->request);
        $this->assertInstanceOf('frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequestInterface', $this->request);
    }


    public function testBindAndMakeHttpResponse()
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
                          return $response = new HttpResponse($this->request);
                      }
            );
        } else {
            // This is the way to bind an HttpRequest in PHP 5.3.x

            $me = $this;
            Client::bind(
                  'httpResponse',
                      function () use ($me) {
                          return new HttpResponse($me->request);
                      }
            );
        }
        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $this->response = Client::make('httpResponse');
        //        echo get_class($this->request);
        $this->assertInstanceOf('frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse', $this->response);
        $decodedBody = json_decode($this->response->body, true);
        $this->assertTrue($decodedBody['server'] == 'arango');
    }
}
