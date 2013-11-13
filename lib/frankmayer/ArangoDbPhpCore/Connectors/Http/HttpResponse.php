<?php

/**
 * ArangoDB PHP Core Client: HTTP Response
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors\Http;


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
    public $status;


    public function __construct($requestObject)
    {
        $this->request = $requestObject;
        $this->doConstruct();
    }

    /**
     */
    public function doConstruct()
    {
        $this->splitResponseToHeadersArrayAndBody($this->request->response);
        $this->status = substr($this->headers['status'], 9, 3);
        // todo 1 Frank do a check if response or request is async ... (update... still needed?)
    }


    /**
     * @param $response
     */
    public function splitResponseToHeadersArrayAndBody($response)
    {
        list($headers, $this->body) = explode("\r\n\r\n", $response, 2);

        $headersArray = explode("\r\n", $headers);
        foreach ($headersArray as $line => $header) {
            if ($line > 0) {
                $pair = explode(":", $header);

                $this->headers[$pair[0]] = $pair[1];
            } else {
                $this->headers['status'] = $header;
            }
        }
    }
}