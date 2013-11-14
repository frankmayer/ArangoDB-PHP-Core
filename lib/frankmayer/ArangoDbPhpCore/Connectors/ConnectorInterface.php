<?php

/**
 * ArangoDB PHP Core Client: Connector Interface
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors;

/**
 * A Connector Interface
 *
 * @package frankmayer\ArangoDbPhpCoreCore
 */
interface  ConnectorInterface
{
    public function instantiateRequestObject($client);

    public function instantiateResponseObject($client);
}
