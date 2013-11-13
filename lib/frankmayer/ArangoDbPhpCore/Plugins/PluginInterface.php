<?php

/**
 * ArangoDB PHP Core Client: Plugin Interface
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Plugins;

/**
 * The Plugin Interface Class which plugins must implement
 *
 * @package frankmayer\ArangoDbPhpCore
 */
interface  PluginInterface
{
    public function notify($eventName, $client, $eventData);
}
