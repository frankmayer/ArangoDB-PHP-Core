<?php

/**
 * ArangoDB PHP client: HTTP Request Interface
 *
 * @package   ArangoDbPhpClient
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols\Http;

use frankmayer\ArangoDbPhpCore\Protocols\RequestInterface;


/**
 * An HttpRequest Interface
 *
 * @package frankmayer\ArangoDbPhpCoreCore
 */
interface HttpRequestInterface extends
    RequestInterface
{
    /**
     * Method to send an HTTP request.
     * All request should be done through this method. Any async or batch handling is done within this method.
     *
     * @return HttpResponse Http Response object
     */
    public function send();


    /**
     * Method to an HTTP batch request
     *
     * @param array  $batchParts
     * @param string $boundary
     *
     * @return mixed
     */
    public function sendBatch($batchParts = [], $boundary = 'XXXbXXX');
}