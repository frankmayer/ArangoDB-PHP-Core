<?php

/**
 * ArangoDB PHP Core Client: HTTP Request
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols\Http;

use frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector;


/**
 * HTTP-Request object that holds a request. Requests are in some cases not directly passed to the server,
 * for instance when a request is destined for a batch.
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class Request extends
    RequestBase implements
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
     * This prepares the "fake" response for batch parts
     */
    private function requestBatchPart()
    {
        //todo: fix hardwired Connector to use injected one.
        // Fake a result so we can move on.
        $this->response = 'HTTP/1.1 202 Accepted' . Connector::HTTP_EOL;
        $this->response .= 'location: /_api/document/0/0' . Connector::HTTP_EOL;
        $this->response .= 'server: triagens GmbH High-Performance HTTP Server' . Connector::HTTP_EOL;
        $this->response .= 'content-type: application/json; charset=utf-8' . Connector::HTTP_EOL;
        $this->response .= 'etag: "0"' . Connector::HTTP_EOL;
        $this->response .= 'client: Close' . Connector::HTTP_EOL . Connector::HTTP_EOL;
        $this->response .= '{"error":false,"_id":"0/0","id":"0","_rev":0,"hasMore":0, "result":[{}], "documents":[{}]}' . Connector::HTTP_EOL . Connector::HTTP_EOL;
    }


    /**
     * Main HTTP Request method.
     * All request should be done through this method. Any async or batch handling is done within this method.
     *
     * @return Response Http Response object
     */
    public function request()
    {
        $this->client->notifyPlugins('beforeRequest', $this);
        if (isset($this->options['async'])) {
            $async = $this->options['async'];
            if (is_bool($async)) {
                $async = var_export($async, true);
            }
            $this->headers['x-arango-async'] = $async;
        }

        if (isset($this->client->arangoDBApiVersion)) {
            $this->headers['x-arango-version'] = $this->client->arangoDBApiVersion;
        }

        if (isset($this->options['isBatchPart']) && $this->options['isBatchPart'] === true) {
            $this->requestBatchPart();
            $this->address = $this->client->endpoint . $this->path;
        } else {
            if (isset($this->options) && (!array_key_exists(
                        'isBatchRequest',
                        $this->options
                    ) || (isset($this->options['isBatchRequest']) && $this->options['isBatchRequest'] !== true))
            ) {
                $this->headers['Content-Type'] = 'application/json';
            }
            $this->address  = $this->client->endpoint . $this->path;
            $this->response = $this->client->connector->request($this);
        }

        return $this->client->doRequest($this);
    }


    /**
     * @param array  $batchParts
     * @param string $boundary
     *
     * @return ResponseInterface
     */
    public function sendBatch(
        $batchParts = [],
        $boundary = 'XXXbXXX'
    ) {
        $this->body = '';
        /** @var $batchPart Response */
        // Reminder... The reason, that at this time the batch-parts are HttpResponses is, because of the quasi "promise" that we have to return immediately
        foreach ($batchParts as $contentId => $batchPart) {
            $this->body .= '--' . $boundary . Connector::HTTP_EOL;
            $this->body .= 'Content-Type: application/x-arango-batchpart' . Connector::HTTP_EOL;
            $this->body .= 'Content-Id: ' . ($contentId + 1) . Connector::HTTP_EOL;

            $this->body .= Connector::HTTP_EOL;
            $this->body .= strtoupper($batchPart->request->method) . ' ' . $batchPart->request->path
                . ' ' . 'HTTP/1.1' . Connector::HTTP_EOL . Connector::HTTP_EOL;
            $this->body .= $batchPart->request->body . Connector::HTTP_EOL;
        }
        $this->body .= '--' . $boundary . '--' . Connector::HTTP_EOL;
        $this->path                    = $this->getDatabasePath() . self::API_BATCH;
        $this->headers['Content-Type'] = 'multipart/form-data; boundary=XXXbXXX';

        $this->method = 'post';

        $this->responseObject = $this->request();
        $this->deconstructBatchResponseBody($this->responseObject, $batchParts, $boundary);

        return $this->responseObject;
    }


    /**
     * @param Response     $responseObject
     * @param Array        $batchParts
     * @param              $boundary
     */
    public function deconstructBatchResponseBody(Response $responseObject, $batchParts, $boundary)
    {
        $batchResponseBody = $responseObject->body;
        $batchResponseBody = rtrim($batchResponseBody, '--' . $boundary . '--');

        $parts = explode('--' . $boundary . Connector::HTTP_EOL, $batchResponseBody);
        array_shift($parts);
        $i = 0;
        foreach ($batchParts as &$batchPart) {

            $batchPartHeaders = self::splitBatchPart($parts[$i]);

            /** @var $batchPart Response */
            $batchPart->request->response = $batchPartHeaders[1];
            $batchPart->build($batchPart->request);
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


    /**
     * @return string
     */
    public function getDatabasePath()
    {
        return '/_db/' . $this->client->database;
    }


    public function buildUrlQuery($urlQueryArray)
    {
        $params = [];
        foreach ($urlQueryArray as $key => $value) {
            $params[] = $key . '=' . $value;
        }

        return '?' . implode('&', $params);
    }


    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param \frankmayer\ArangoDbPhpCore\Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return \frankmayer\ArangoDbPhpCore\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param \frankmayer\ArangoDbPhpCore\ConnectorInterface $connector
     */
    public function setConnector($connector)
    {
        $this->connector = $connector;
    }

    /**
     * @return \frankmayer\ArangoDbPhpCore\ConnectorInterface
     */
    public function getConnector()
    {
        return $this->connector;
    }

    /**
     * @param array $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }
    // todo 1 Frank Revisit this method and getter/setter

    //    /**
    //     * @param \frankmayer\ArangoDbPhpCore\Connectors\ResponseInterface $responseObject
    //     */
    //    public function setResponseObject($responseObject)
    //    {
    //        $this->responseObject = $responseObject;
    //    }
    //
    //    /**
    //     * @return \frankmayer\ArangoDbPhpCore\Connectors\ResponseInterface
    //     */
    //    public function getResponseObject()
    //    {
    //        return $this->responseObject;
    //    }
    // todo 1 Frank Revisit this method and getter/setter
    //    /**
    //     * @param string $type
    //     */
    //    public function setType($type)
    //    {
    //        $this->type = $type;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function getType()
    //    {
    //        return $this->type;
    //    }
}