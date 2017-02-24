<?php

/**
 * ArangoDB PHP Core Client: HTTP Response
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols\Http;

use frankmayer\ArangoDbPhpCore\ServerException;


/**
 * Http-Response object holding the raw and objectified Response data.
 *
 * @package frankmayer\ArangoDbPhpCore\Protocols\Http
 */
class HttpResponse implements HttpResponseInterface
{
    /**
     * @var array An array with the http status codes of the ones, that we want to raise an exception for.
     *
     * This should be set with an array like array(400,401,402,403);
     */
    public $enabledHttpServerExceptions;

    /**
     * @var HttpRequest $request The request object
     */
    public $request;
    /**
     * @var array $headers The headers in form of an multidimensional array
     */
    public $headers = [];
    /**
     * @var string $body The body of the response
     */
    public $body = '';
    /**
     * @var array $batch An array of batch parts
     */
    public $batch = [];
    /**
     * @var string $protocol The protocol used
     */
    public $protocol = '';
    /**
     * @var int $status The http status code of the response
     */
    public $status = 0;
    /**
     * @var string $statusPhrase The associated text to the status code
     */
    public $statusPhrase = '';
    /**
     * @var bool $verboseExtractStatusLine get only status code or also the accompanying text
     */
    public $verboseExtractStatusLine = false;
    /**
     * @var string|int This holds the Batchpart Content-Id of the response if one was supplied to ArangoDB in the request.
     */
    public $batchContentId;


    /**
     *
     */
    public function __construct()
    {
        // 404 intentionally left out as a default, because not finding data, shouldn't raise an exception
        $this->enabledHttpServerExceptions = [400, 401, 403, 405, 412, 500, 600, 601];
    }


    /**
     * @param $request
     *
     * @return HttpResponseInterface
     * @throws ServerException
     *
     */
    public function build(HttpRequestInterface $request): HttpResponseInterface
    {
        if ($request instanceof AbstractHttpRequest) {
            $response      = $request->response;
            $this->request = $request;

            if ($request->batch === true) {
                $boundary    = $request->batchBoundary;
                $this->batch = $this->deconstructBatchResponseBody($response, $boundary);
            }
        } else {
            $response = $request;
        }
        $this->splitResponseToHeadersArrayAndBody($response);
        $statusLineArray = explode(' ', trim($this->headers['status'][0]));

        $this->status = (int) $statusLineArray[1];

        //todo: Revisit this:
        if ($this->verboseExtractStatusLine === true) {
            $this->protocol = $statusLineArray[0];

            $this->statusPhrase = $this->decodeGetStatusPhrase($statusLineArray);
        }

        if (in_array((int) $this->status, $this->enabledHttpServerExceptions, true)) {
            // Ignoring this for now.
            // @codeCoverageIgnoreStart
            $this->protocol     = $statusLineArray[0];
            $this->statusPhrase = $this->decodeGetStatusPhrase($statusLineArray);
            throw new ServerException($this->statusPhrase, $this->status);
            // @codeCoverageIgnoreEnd
        }

        //todo: End revisit this:

        return $this;
    }


    /**
     * @param string $responseMessage
     *
     * @return HttpResponseInterface
     * @throws ServerException
     */
    public function buildBatch(string $responseMessage): HttpResponseInterface
    {
        $response = $responseMessage;
        $this->splitResponseToHeadersArrayAndBody($response);
        $statusLineArray = explode(' ', trim($this->headers['status'][0]));

        $this->status = (int) $statusLineArray[1];

        //todo: Revisit this:
        if ($this->verboseExtractStatusLine === true) {
            $this->protocol = $statusLineArray[0];

            $this->statusPhrase = $this->decodeGetStatusPhrase($statusLineArray);
        }

        if (in_array((int) $this->status, $this->enabledHttpServerExceptions, true)) {
            // Ignoring this for now.
            // @codeCoverageIgnoreStart
            $this->protocol     = $statusLineArray[0];
            $this->statusPhrase = $this->decodeGetStatusPhrase($statusLineArray);
            throw new ServerException($this->statusPhrase, $this->status);
            // @codeCoverageIgnoreEnd
        }

        //todo: End revisit this:

        return $this;
    }


    /**
     * @param array $statusLineArray
     *
     * @return string
     */
    protected function decodeGetStatusPhrase(array $statusLineArray = []): string
    {
        $phrase = '';
        foreach ($statusLineArray as $key => $part) {
            if ($key > 1) {
                $phrase .= $part . ' ';
            }
        }
        $phrase = trim($phrase, ' ');

        return $phrase;
    }


    /**
     * Splits the response data to a Headers array and a body
     *
     * It expects the response data to be in $this->request->response
     * It puts the headers into $this->headers and
     * the body into $this->body
     *
     * @param string $response
     *
     * @return HttpResponseInterface
     */
    protected function splitResponseToHeadersArrayAndBody(string $response): HttpResponseInterface
    {
        //        $tmp = explode("\r\n\r\n", $response, 2);
        //        var_export($tmp);
        list($headers, $this->body) = explode("\r\n\r\n", $response, 2);

        $this->headers = $this->getHeaderArray($headers);

        return $this;
    }


    /**
     * @param string $batchResponseBody
     * @param string $boundary
     *
     * @return array
     * @throws ServerException
     */
    public function deconstructBatchResponseBody(string $batchResponseBody, string $boundary): array
    {
        $connector         = $this->request->client->connector;
        $batchObjects      = [];
        $batchResponseBody = rtrim($batchResponseBody, '--' . $boundary . '--');

        $batchParts = explode('--' . $boundary . $connector::HTTP_EOL, $batchResponseBody);
        array_shift($batchParts);
        $i                   = 0;
        $batchObjectTemplate = new static();

        foreach ($batchParts as &$batchPart) {
            /** @var $batchPart HttpResponse */
            $batchPartHeaders = static::splitBatchPart($batchPart);
            $batchObject      = clone $batchObjectTemplate;
            $batchObject->buildBatch($batchPartHeaders[1]);
            //            $batchArangoHeader  = explode(PHP_EOL, $batchPartHeaders[0]);
            $batchArangoHeaderArray = $this->getHeaderArray($batchPartHeaders[0]);
            if (array_key_exists('Content-Id', $batchArangoHeaderArray)) {
                $batchObject->batchContentId = $batchArangoHeaderArray['Content-Id'][0];
            }
            $batchObjects[] = $batchObject;
            $i++;
        }

        return $batchObjects;
    }


    /**
     * @param $batchPart
     *
     * @return array
     */
    public static function splitBatchPart(string $batchPart): array
    {
        return explode("\r\n\r\n", trim($batchPart), 2);
    }


    /**
     * @param mixed $batch
     *
     * @return HttpResponseInterface
     */
    public function setBatch($batch): HttpResponseInterface
    {
        $this->batch = $batch;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBatch()
    {
        return $this->batch;
    }

    /**
     * @param string $body
     *
     * @return HttpResponseInterface
     */
    public function setBody(string $body): HttpResponseInterface
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array $headers
     *
     * @return HttpResponseInterface
     */
    public function setHeaders(array $headers = []): HttpResponseInterface
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param HttpRequestInterface $request
     *
     * @return HttpResponseInterface
     */
    public function setRequest(HttpRequestInterface $request): HttpResponseInterface
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return HttpRequest|HttpRequestInterface
     */
    public function getRequest(): HttpRequestInterface
    {
        return $this->request;
    }

    /**
     * @param mixed $status
     *
     * @return HttpResponseInterface
     */
    public function setStatus($status): HttpResponseInterface
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return integer
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @return string
     */
    public function getStatusPhrase(): string
    {
        return $this->statusPhrase;
    }

    /**
     * @param boolean $verboseStatusLine
     *
     * @return HttpResponseInterface
     */
    public function setVerboseExtractStatusLine($verboseStatusLine)
    {
        $this->verboseExtractStatusLine = $verboseStatusLine;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getVerboseExtractStatusLine()
    {
        return $this->verboseExtractStatusLine;
    }

    /**
     * @param string $headers
     *
     * @return array
     */
    protected function getHeaderArray(string $headers): array
    {
        $headerArray  = [];
        $headersArray = explode("\r\n", trim($headers));
        foreach ($headersArray as $line => $header) {
            if ($line > 0) {
                $pair                    = explode(': ', $header);
                $headerArray[$pair[0]][] = $pair[1];
            } else {
                $headerArray['status'][] = $header;
            }
        }

        return $headerArray;
    }
}
