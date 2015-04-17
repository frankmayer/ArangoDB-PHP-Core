<?php

/**
 * ArangoDB PHP Core Client: HTTP Response
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols\Http;

use frankmayer\ArangoDbPhpCore\ServerException;


/**
 * Http-Response object holding the raw and objectified Response data.
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class Response
{
    /**
     * @var array An array with the http status codes of the ones, that we want to raise an exception for.
     *
     * This should be set with an array like array(400,401,402,403);
     */
    public $enabledHttpServerExceptions;


    /**
     * @var Request $request
     */
    public $request;
    public $headers                  = [];
    public $body;
    public $batch;
    public $async;
    public $protocol;
    public $status;
    public $statusPhrase;
    public $verboseExtractStatusLine = false;

    public function __construct()
    {
        // 404 intentionally left out as a default, because not finding data, shouldn't raise an exception
        $this->enabledHttpServerExceptions = [400, 401, 403, 405, 412, 500, 600, 601];
    }

    /**
     * @param $request
     *
     * @return $this
     * @throws ServerException
     *
     */
    public function build($request)
    {
        $response = $request->response;
        $this->splitResponseToHeadersArrayAndBody($response);
        $statusLineArray = explode(" ", $this->headers['status']);

        $this->status = $statusLineArray[1];

        if ($this->verboseExtractStatusLine === true) {
            $this->protocol = $statusLineArray[0];

            $this->statusPhrase = $this->decodeGetStatusPhrase($statusLineArray);
        }

        if (in_array(intval($this->status), $this->enabledHttpServerExceptions)) {
            // Ignoring this, as the server needs to have authentication enabled in order to run through this.
            // @codeCoverageIgnoreStart
            $this->protocol     = $statusLineArray[0];
            $this->statusPhrase = $this->decodeGetStatusPhrase($statusLineArray);
            throw new ServerException($this->statusPhrase, $this->status);
            // @codeCoverageIgnoreEnd
        }

        return $this;
    }

    /**
     * @param $statusLineArray
     *
     * @return string
     */
    public function decodeGetStatusPhrase($statusLineArray)
    {
        $phrase = '';
        foreach ($statusLineArray as $key => $part) {
            if ($key > 1) {
                $phrase .= $part . ' ';
            }
        }
        $phrase = trim($phrase, " ");

        return $phrase;
    }


    /**
     * Splits the response data to a Headers array and a body
     *
     * It expects the response data to be in $this->request->response
     * It puts the headers into $this->headers and
     * the body into $this->body
     *
     * @param $response
     */
    protected function splitResponseToHeadersArrayAndBody($response)
    {
        list($headers, $this->body) = explode("\r\n\r\n", $response, 2);

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
     * @param \frankmayer\ArangoDbPhpCore\Protocols\Http\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Request
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