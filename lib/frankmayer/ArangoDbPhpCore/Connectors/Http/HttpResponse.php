<?php

/**
 * ArangoDB PHP Core Client: HTTP Response
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors\Http;

use frankmayer\ArangoDbPhpCore\ServerException;


/**
 * Http-Response object holding the raw and objectified Response data.
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class HttpResponse
{
    /**
     * @var HttpRequest $request
     */
    public $request;
    public $headers = array();
    public $body;
    public $batch;
    public $async;
    public $protocol;
    public $status;
    public $statusPhrase;
    public $verboseExtractStatusLine = false;


    /**
     */
    public function doConstruct()
    {
        $this->splitResponseToHeadersArrayAndBody();
        $statusLineArray = explode(" ", $this->headers['status']);

        $this->status = $statusLineArray[1];

        if ($this->verboseExtractStatusLine === true) {
            $this->protocol     = $statusLineArray[0];
            $this->statusPhrase = $statusLineArray[2];
        }

        if ($this->status === '401') {
            // Ignoring this, as the server needs to have authentication enabled in order to run through this.
            // @codeCoverageIgnoreStart
            $this->protocol     = $statusLineArray[0];
            $this->statusPhrase = $statusLineArray[2];
            throw new ServerException($this->statusPhrase, $this->status);
            // @codeCoverageIgnoreEnd
        }
    }


    /**
     * Splits the response data to a Headers array and a body
     *
     * It expects the response data to be in $this->request->response
     * It puts the headers into $this->headers and
     * the body into $this->body
     */
    protected function splitResponseToHeadersArrayAndBody()
    {
        list($headers, $this->body) = explode("\r\n\r\n", $this->request->response, 2);

        $headersArray = explode("\r\n", $headers);
        foreach ($headersArray as $line => $header) {
            if ($line > 0) {
                $pair = explode(": ", $header);

                $this->headers[$pair[0]] = $pair[1];
            } else {
                $this->headers['status'] = $header;
            }
        }
    }

    /**
     * @param mixed $async
     */
    public function setAsync($async)
    {
        $this->async = $async;
    }

    /**
     * @return mixed
     */
    public function getAsync()
    {
        return $this->async;
    }

    /**
     * @param mixed $batch
     */
    public function setBatch($batch)
    {
        $this->batch = $batch;
    }

    /**
     * @return mixed
     */
    public function getBatch()
    {
        return $this->batch;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
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
     * @param \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @return mixed
     */
    public function getStatusPhrase()
    {
        return $this->statusPhrase;
    }

    /**
     * @param boolean $verboseStatusLine
     */
    public function setVerboseExtractStatusLine($verboseStatusLine)
    {
        $this->verboseExtractStatusLine = $verboseStatusLine;
    }

    /**
     * @return boolean
     */
    public function getVerboseExtractStatusLine()
    {
        return $this->verboseExtractStatusLine;
    }
}