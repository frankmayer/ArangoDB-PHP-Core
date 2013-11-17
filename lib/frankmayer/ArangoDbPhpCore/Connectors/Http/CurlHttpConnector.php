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
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequestInterface;
use frankmayer\ArangoDbPhpCore\ServerException;


/**
 * This connector acts as a wrapper to PHP's curl class.
 * It must be injected into the client object upon the client's creation.
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


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->verbose = false;
    }


    /**
     * @param HttpRequest|HttpRequestInterface $request
     *
     * @throws \frankmayer\ArangoDbPhpCore\ServerException
     * @return mixed
     */
    public function request(HttpRequestInterface $request)
    {
        $curlHeaders = array();

        $ch   = curl_init($request->address);
        $body = $request->body;

        $request->headers['Content-Length'] = strlen($body);

        foreach ($request->headers as $headerKey => $headerVal) {
            $curlHeaders[] = $headerKey . ': ' . $headerVal;
        }

        curl_setopt_array(
            $ch,
            array(
                 CURLOPT_CUSTOMREQUEST  => $request->method,
                 CURLOPT_VERBOSE        => $this->verbose,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_HEADER         => true,
                 CURLOPT_POSTFIELDS     => $body,
                 CURLOPT_HTTPHEADER     => $curlHeaders
            )
        );

        $response = curl_exec($ch);
        if ($response === false) {
            throw new ServerException(curl_error($ch), curl_errno($ch));
        }

        return $response;
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
     * @param boolean $verbose
     */
    public function setVerbose($verbose)
    {
        $this->verbose = $verbose;
    }

    /**
     * @return boolean
     */
    public function getVerbose()
    {
        return $this->verbose;
    }

}
