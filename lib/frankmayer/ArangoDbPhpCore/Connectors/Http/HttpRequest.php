<?php

/**
 * ArangoDB PHP Core Client: HTTP Request
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors\Http;

use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\Connectors\ResponseInterface;


/**
 * HTTP-Request object that holds a request. Requests are in some cases not directly passed to the server,
 * for instance when a request is destined for a batch.
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class HttpRequest implements
    HttpRequestInterface
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
     * @var \frankmayer\ArangoDbPhpCore\Connectors\ConnectorInterface
     */
    public $connector;
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
    public $headers;
    /**
     * @var array The options of the request
     */
    public $options;
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
     * HTTP Request constructor.
     * Constructs an HTTP Request object which can be configured appropriately and executed in order to return an HTTPResponse object.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client    = $client;
        $this->connector = $client->connector;
    }


    /**
     * This prepares the "fake" response for batch parts
     */
    private function requestBatchPart()
    {
        // Fake a result so we can move on.
        $this->response = 'HTTP/1.1 202 Accepted' . HttpConnector::HTTP_EOL;
        $this->response .= 'location: /_api/document/0/0' . HttpConnector::HTTP_EOL;
        $this->response .= 'server: triagens GmbH High-Performance HTTP Server' . HttpConnector::HTTP_EOL;
        $this->response .= 'content-type: application/json; charset=utf-8' . HttpConnector::HTTP_EOL;
        $this->response .= 'etag: "0"' . HttpConnector::HTTP_EOL;
        $this->response .= 'client: Close' . HttpConnector::HTTP_EOL . HttpConnector::HTTP_EOL;
        $this->response .= '{"error":false,"_id":"0/0","id":"0","_rev":0,"hasMore":0, "result":[{}], "documents":[{}]}' . HttpConnector::HTTP_EOL . HttpConnector::HTTP_EOL;
    }


    /**
     * Main HTTP Request method.
     * All request should be done through this method. Any async or batch handling is done within this method.
     *
     * @return HttpResponse Http Response object
     */
    public function request()
    {
        if (isset($this->options['async'])) {
            $async = $this->options['async'];
            if (is_bool($async)) {
                $async = var_export($async, true);
            }
            $this->headers['x-arango-async'] = $async;
        }

        if (isset($this->client->arangodbApiVersion)) {
            $this->headers['x-arango-version'] =  $this->client->arangodbApiVersion;
        }

        if (isset($this->options['isBatchPart']) && $this->options['isBatchPart'] === true) {
            $this->requestBatchPart();
            $this->address = $this->client->endpoint . $this->path;
        } else {
            if (isset($this->options['isBatchRequest']) && $this->options['isBatchRequest'] === false) {
                $this->headers['Content-Type'] = 'application/json';
            }
            $this->address  = $this->client->endpoint . $this->path;
            $this->response = $this->connector->request($this);
        }

        return $this->client->connector->instantiateResponseObject($this);
    }


    /**
     * @param array  $batchParts
     * @param string $boundary
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function sendBatch(
        $batchParts = array(),
        $boundary = 'XXXbXXX'
    ) {
        $this->body = '';
        /** @var $batchPart HttpResponse */
        // Reminder... The reason, that at this time the batch-parts are HttpResponses is, because of the quasi "promise" that we have to return immediately
        foreach ($batchParts as $contentId => $batchPart) {
            $this->body .= '--' . $boundary . HttpConnector::HTTP_EOL;
            $this->body .= 'Content-Type: application/x-arango-batchpart' . HttpConnector::HTTP_EOL;
            $this->body .= 'Content-Id: ' . ($contentId + 1) . HttpConnector::HTTP_EOL;

            $this->body .= HttpConnector::HTTP_EOL;
            $this->body .= strtoupper($batchPart->request->method) . ' ' . $batchPart->request->path
                . ' ' . 'HTTP/1.1' . HttpConnector::HTTP_EOL . HttpConnector::HTTP_EOL;
            $this->body .= $batchPart->request->body . HttpConnector::HTTP_EOL;
        }
        $this->body .= '--' . $boundary . '--' . HttpConnector::HTTP_EOL;
        $this->path      = $this->client->getDatabasePath() . self::API_BATCH;
        $this->headers['Content-Type'] = 'multipart/form-data; boundary=XXXbXXX';

        $this->method = 'post';

        $this->responseObject = $this->request();
        $this->deconstructBatchResponseBody($this->responseObject, $batchParts, $boundary);

        return $this->responseObject;
    }


    /**
     * @param HttpResponse $responseObject
     * @param Array        $batchParts
     * @param              $boundary
     */
    public function deconstructBatchResponseBody(HttpResponse $responseObject, $batchParts, $boundary)
    {
        $batchResponseBody = $responseObject->body;
        $batchResponseBody = rtrim($batchResponseBody, '--' . $boundary . '--');

        $parts = explode('--' . $boundary . HttpConnector::HTTP_EOL, $batchResponseBody);
        array_shift($parts);
        $i = 0;
        foreach ($batchParts as &$batchPart) {

            $batchPartHeaders = self::splitBatchPart($parts[$i]);

            /** @var $batchPart HttpResponse */
            $batchPart->request->response = $batchPartHeaders[1];
            $batchPart->doConstruct($batchPart->request);
            $i++;
        }
        $responseObject->batch = $batchParts;
    }


    /**
     * @param $batchPart
     *
     * @return array
     */
    public static function splitBatchPart($batchPart)
    {
        $parts = explode("\r\n\r\n", $batchPart, 2);

        return $parts;
    }
}