<?php

/**
 * ArangoDB PHP Core Client: Request Interface
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols;


/**
 * A Request Interface
 *
 * @package frankmayer\ArangoDbPhpCoreCore
 */
interface RequestInterface
{
    /**
     * Method to send a request.
     * All request should be done through this method. Any async or batch handling is done within this method.
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\ResponseInterface Response object
     */
    public function send();
}