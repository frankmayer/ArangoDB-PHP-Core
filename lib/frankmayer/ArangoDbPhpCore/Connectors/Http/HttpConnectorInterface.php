<?php

/**
 * ArangoDB-PHP-Core client: HTTP Connector Interface
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors\Http;

use frankmayer\ArangoDbPhpCore\Connectors\ConnectorInterface;


/**
 * Provides access to the ArangoDB server
 * As all access is done using HTTP, we do not need to establish a
 * persistent client and keep its state.
 * Instead, clients are established on the fly for each request
 * and are destroyed afterwards.
 *
 * @package frankmayer\ArangoDbPhpCore
 */
interface  HttpConnectorInterface extends
    ConnectorInterface
{

    public function request(HttpRequestInterface $request);

    public function instantiateRequestObject($client);

    public function instantiateResponseObject($request);


}
