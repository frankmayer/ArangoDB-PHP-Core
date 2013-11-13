<?php

/**
 * ArangoDB PHP Core Client: Curl HTTP Connector
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors\Http;

use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\ServerException;


/**
 * This connector provides the connection to PHP's curl class.
 * It must be injected into the client object upon the client's creation.
 *
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class CurlHttpConnector extends
    HttpConnector implements
    HttpConnectorInterface
{

    /**
     * @var bool switch for turning on curl verbose logging
     */
    protected $verbose;

    /**
     * @var Client client property
     */
    protected $client;


    public function __construct()
    {
        $this->verbose = false;
    }


    /**
     * @param HttpRequest $request
     *
     * @throws \frankmayer\ArangoDbPhpCore\ServerException
     * @return mixed
     */
    public function request(HttpRequest $request)
    {
        $ch        = curl_init($request->address);
        $body      = $request->body;
        $headers   = $request->headers;
        $headers[] = 'Content-Length: ' . strlen($body);

        curl_setopt_array(
            $ch,
            array(
                 CURLOPT_CUSTOMREQUEST  => $request->method,
                 CURLOPT_VERBOSE        => $this->verbose,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_HEADER         => true,
                 CURLOPT_POSTFIELDS     => $body,
                 CURLOPT_HTTPHEADER     => $headers
            )
        );
        $response = curl_exec($ch);
        if ($response === false) {
            throw new ServerException(curl_error($ch), curl_errno($ch));
        }

        return $response;
    }


    /**
     * @param HttpRequest $request
     *
     * @return mixed
     */
    public function get(HttpRequest $request)
    {
        return $this->request($request);
    }


    /**
     * @param HttpRequest $request
     *
     * @return mixed
     */
    public function post(HttpRequest $request)
    {
        return $this->request($request);
    }

    /**
     * @param HttpRequest $request
     *
     * @return mixed
     */
    public function delete(HttpRequest $request)
    {
        return $this->request($request);
    }

    /**
     * @param HttpRequest $request
     *
     * @return mixed
     */
    public function put(HttpRequest $request)
    {
        return $this->request($request);
    }

    /**
     * @param $client
     *
     * @return HttpRequest
     */
    public function instantiateRequestObject($client)
    {
        return new $client->getRequest($client);
    }

    /**
     * @param HttpRequest $request
     *
     * @return HttpResponse
     */
    public function instantiateResponseObject($request)
    {
        $client = $request->client;

        return $client->doRequest($request);
    }
}
