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
 * An HttpConnector Interface
 *
 * @package frankmayer\ArangoDbPhpCore
 */
interface  HttpConnectorInterface extends
    ConnectorInterface
{
    public function request(HttpRequestInterface $request);
}
