<?php

/**
 * ArangoDB PHP Core Client: HTTP Connector
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors\Http;


/**
 * Provides access to the ArangoDB server
 * As all access is done using HTTP, we do not need to establish a
 * persistent client and keep its state.
 * Instead, clients are established on the fly for each request
 * and are destroyed afterwards.
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class HttpConnector
{
    const HTTP_EOL = "\r\n";


}
