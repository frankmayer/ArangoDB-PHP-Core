<?php

/**
 * ArangoDB PHP Core Client: Request Interface
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols;

//todo: Revisit those base classes and solve strict typing issues in classes (type hinting on parameters. This vs HttpRequestInterface).

/**
 * A Request Interface
 *
 * @package frankmayer\ArangoDbPhpCore\Protocols
 */
interface RequestInterface
{
    /**
     * Method to send a request.
     * All request should be done through this method. Any async or batch handling is done within this method.
     *
     * @return ResponseInterface Response object
     */
    public function send();
}
