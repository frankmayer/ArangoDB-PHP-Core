<?php

/**
 * ArangoDB PHP Core Client: HTTP Request
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols\Http;


/**
 * HTTP-Request object that holds a request. Requests are in some cases not directly passed to the server,
 * for instance when a request is destined for a batch.
 *
 * @package frankmayer\ArangoDbPhpCore
 */
abstract class RequestBase implements
    RequestInterface
{

    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_PATCH   = 'PATCH';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_HEAD    = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';

    const API_BATCH = '/_api/batch';


    /**
     * @var \frankmayer\ArangoDbPhpCore\Client
     */
    public $client;
    /**
     * @var \frankmayer\ArangoDbPhpCore\ConnectorInterface
     */
    public $connector;
    /**
     * @var array
     */
    public $connectorOptions = [];
    /**
     * @var string The address of the endpoint
     */
    public $address;
    /**
     * @var string The path-part of the request
     */
    public $path;
    /**
     * @var string The Body of the request
     */
    public $body;
    /**
     * @var array The headers of the request
     */
    public $headers = [];
    /**
     * @var array The options of the request
     */
    public $options = [];
    /**
     * @var string The type of the request
     */
    public $type;
    /**
     * @var string The method of the request
     */
    public $method;
    /**
     * @var string The response data as a result of the request
     */
    public $response;
    /**
     * @var ResponseInterface The response-object as a result of the request
     */
    public $responseObject;
    /**
     * @var object The wrapped handler of communications.
     */
    public $handler;
}