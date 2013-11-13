<?php

/**
 * ArangoDB PHP Core Client: FSock Client Connector
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
class FsockHttpConnector extends
    HttpConnector
{

    // stub class
    // todo 0 Anyone to implement fsock connector?
}
