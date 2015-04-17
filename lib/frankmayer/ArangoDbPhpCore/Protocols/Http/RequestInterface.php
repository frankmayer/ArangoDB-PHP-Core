<?php

/**
 * ArangoDB PHP client: HTTP Request Interface
 *
 * @package   ArangoDbPhpClient
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols\Http;


/**
 * An HttpRequest Interface
 *
 * @package frankmayer\ArangoDbPhpCoreCore
 */
interface RequestInterface extends
    \frankmayer\ArangoDbPhpCore\RequestInterface
{
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