<?php

/**
 * ArangoDB PHP Core Client: Curl HTTP Connector
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors\Http;

use frankmayer\ArangoDbPhpCore\ClientOptions;
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
    protected $verboseLogging;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->verboseLogging = false;
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
                 CURLOPT_VERBOSE        => $this->verboseLogging,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_HEADER         => true,
                 CURLOPT_POSTFIELDS     => $body,
                 CURLOPT_HTTPHEADER     => $curlHeaders
            )
        );

        $clientOptions = $request->client->clientOptions;
        // Ignoring this, as the server needs to have authentication enabled in order to run through this.
        // @codeCoverageIgnoreStart
        if (isset ($clientOptions[ClientOptions::OPTION_AUTH_TYPE])) {
            if (strtolower($clientOptions[ClientOptions::OPTION_AUTH_TYPE]) === 'basic') {
                curl_setopt(
                    $ch,
                    CURLOPT_USERPWD,
                    $clientOptions[ClientOptions::OPTION_AUTH_USER] . ":" . $clientOptions[ClientOptions::OPTION_AUTH_PASSWD]
                );
            }
        }
        // @codeCoverageIgnoreEnd

        $response = curl_exec($ch);
        if ($response === false) {
            throw new ServerException(curl_error($ch), curl_errno($ch));
        }


        return $response;
    }


    /**
     * @param boolean $verbose
     */
    public function setVerboseLogging($verbose)
    {
        $this->verboseLogging = $verbose;
    }

    /**
     * @return boolean
     */
    public function getVerboseLogging()
    {
        return $this->verboseLogging;
    }
}
