<?php

/**
 * ArangoDB-PHP-Core client: HTTP Connector Interface
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols\Http;

use frankmayer\ArangoDbPhpCore\ConnectorInterface;


/**
 * An HttpConnector Interface
 *
 * @package frankmayer\ArangoDbPhpCore\Protocols\Http
 */
interface  HttpConnectorInterface extends ConnectorInterface
{
    /**
     * @param HttpRequestInterface $request
     *
     * @return mixed
     */
    public function send(HttpRequestInterface $request);
}
