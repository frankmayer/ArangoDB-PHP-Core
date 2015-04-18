<?php

/**
 * ArangoDB-PHP-Core client: HTTP Connector Interface
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols\Http;


/**
 * An HttpConnector Interface
 *
 * @package frankmayer\ArangoDbPhpCore
 */
interface  ConnectorInterface extends
    \frankmayer\ArangoDbPhpCore\ConnectorInterface

{
    public function request(Request $request);
}
