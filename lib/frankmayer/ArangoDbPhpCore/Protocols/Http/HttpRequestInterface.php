<?php

/**
 * ArangoDB PHP client: HTTP Request Interface
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols\Http;

use frankmayer\ArangoDbPhpCore\Protocols\RequestInterface;


/**
 * An HttpRequest Interface
 *
 * @package frankmayer\ArangoDbPhpCore\Protocols\Http
 */
interface HttpRequestInterface extends RequestInterface
{
    /**
     * Method to send an HTTP request.
     * All request should be done through this method. Any async or batch handling is done within this method.
     *
     * @return HttpResponseInterface Http Response object
     */
    public function send(): HttpResponseInterface;


    /**
     * Method to an HTTP batch request
     *
     * @param array  $batchParts
     * @param string $boundary
     *
     * @return HttpResponseInterface
     */
    public function sendBatch(array $batchParts = [], $boundary = 'XXXbXXX'): HttpResponseInterface;
}
