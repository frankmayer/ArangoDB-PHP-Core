<?php

/**
 * ArangoDB PHP Core Client: HTTP Request
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors\Http;


/**
 * HTTP-Request object that holds a request. Requests are in some cases not directly passed to the server,
 * for instance when a request is destined for a batch.
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class HttpRequest extends
    HttpRequestBase implements
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
            $this->headers['x-arango-version'] = $this->client->arangodbApiVersion;
        }

        if (isset($this->options['isBatchPart']) && $this->options['isBatchPart'] === true) {
            $this->requestBatchPart();
            $this->address = $this->client->endpoint . $this->path;
        } else {
            if (isset($this->options['isBatchRequest']) && $this->options['isBatchRequest'] === false) {
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
        $this->path                    = $this->client->getDatabasePath() . self::API_BATCH;
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
     * @param \frankmayer\ArangoDbPhpCore\Connectors\ConnectorInterface $connector
     */
    public function setConnector($connector)
    {
        $this->connector = $connector;
    }

    /**
     * @return \frankmayer\ArangoDbPhpCore\Connectors\ConnectorInterface
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