<?php

/**
 * ArangoDB PHP Core Client: HTTP Request
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols\Http;


/**
 * HTTP-Request object that holds a request. Requests are in some cases not directly passed to the server,
 * for instance when a request is destined for a batch.
 *
 * @package frankmayer\ArangoDbPhpCore\Protocols\Http
 */
use frankmayer\ArangoDbPhpCore\ClientInterface;
use frankmayer\ArangoDbPhpCore\ConnectorInterface;

/**
 * Class HttpRequest
 *
 * @package frankmayer\ArangoDbPhpCore\Protocols\Http
 */
class HttpRequest extends AbstractHttpRequest
{
    /**
     * Method to send an HTTP request.
     * All request should be done through this method. Any async or batch handling is done within this method.
     *
     * @return HttpResponseInterface Http Response object
     */
    public function send(): HttpResponseInterface
    {
        $this->client->notifyPlugins('beforeRequest', [$this]);
        if (isset($this->options['async'])) {
            $async = $this->options['async'];
            if (is_bool($async)) {
                $async = var_export($async, true);
            }
            $this->headers['x-arango-async'] = $async;
        }

        if (isset($this->options['isBatchPart']) && $this->options['isBatchPart'] === true) {
            //            $this->isBatchPart = true;
            $this->address = $this->client->endpoint . $this->path;

            //todo: Revisit this:
            return true;
        } else {
            if (null !== $this->options && (!array_key_exists(
                        'isBatchRequest',
                        $this->options
                    ) || (isset($this->options['isBatchRequest']) && $this->options['isBatchRequest'] !== true))
            ) {
                $this->headers['Content-Type'] = 'application/json';
            }
            $this->address  = $this->client->endpoint . $this->path;
            $this->response = $this->client->connector->send($this);
        }

        return $this->client->doRequest($this);
    }

// todo: Revisit batch functionality. Does it need to be coupled to the request?
    /**
     * @param array  $batchParts
     * @param string $boundary
     *
     * @return HttpResponseInterface
     *
     * @codeCoverageIgnore There is no unit-test for this ATM. However, the functionality is tested by integration tests from higher level clients like Core-Guzzle
     */
    public function sendBatch(array $batchParts = [], $boundary = 'XXXbXXX'): HttpResponseInterface
    {
        $connector  = $this->client->connector;
        $this->body = '';
        /** @var $batchPart AbstractHttpRequest */
        // Reminder... The reason, that at this time the batch-parts are HttpResponses is, because of the quasi "promise" that we have to return immediately
        foreach ($batchParts as $contentId => $batchPart) {
            $this->body .= '--' . $boundary . $connector::HTTP_EOL;
            $this->body .= 'Content-Type: application/x-arango-batchpart' . $connector::HTTP_EOL;
            $this->body .= 'Content-Id: ' . ($contentId + 1) . $connector::HTTP_EOL;

            $this->body .= $connector::HTTP_EOL;
            $this->body .= strtoupper($batchPart->method) . ' ' . $batchPart->path
                . ' ' . 'HTTP/1.1' . $connector::HTTP_EOL . $connector::HTTP_EOL;
            $this->body .= $batchPart->body . $connector::HTTP_EOL;
        }
        $this->body .= '--' . $boundary . '--' . $connector::HTTP_EOL;
        $this->path                    = $this->client->fullDatabasePath . self::API_BATCH;
        $this->headers['Content-Type'] = 'multipart/form-data; ' . $boundary;

        $this->method         = 'post';
        $this->batch          = true;
        $this->batchBoundary  = $boundary;
        $this->batchParts     = $batchParts;
        $this->responseObject = $this->send();

        return $this->responseObject;
    }


    /**
     * @param string $address
     *
     * @return HttpRequestInterface
     */
    public function setAddress(string $address): HttpRequestInterface
    {
        $this->address = $address;

        return $this;
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
     *
     * @return HttpRequestInterface
     */
    public function setBody(string $body): HttpRequestInterface
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param ClientInterface $client
     *
     * @return HttpRequestInterface
     */
    public function setClient(ClientInterface $client): HttpRequestInterface
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @param ConnectorInterface $connector
     *
     * @return HttpRequestInterface
     */
    public function setConnector(ConnectorInterface $connector): HttpRequestInterface
    {
        $this->connector = $connector;

        return $this;
    }

    /**
     * @return ConnectorInterface
     */
    public function getConnector(): ConnectorInterface
    {
        return $this->connector;
    }

    /**
     * @param array $headers
     *
     * @return HttpRequestInterface
     */
    public function setHeaders(array $headers = []): HttpRequestInterface
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $method
     *
     * @return HttpRequestInterface
     */
    public function setMethod(string $method): HttpRequestInterface
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param array $options
     *
     * @return HttpRequestInterface
     */
    public function setOptions(array $options): HttpRequestInterface
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param string $path
     *
     * @return HttpRequestInterface
     */
    public function setPath(string $path): HttpRequestInterface
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $response
     *
     * @return HttpRequestInterface
     */
    public function setResponse(string $response): HttpRequestInterface
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return string
     */
    public function getResponse(): string
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
