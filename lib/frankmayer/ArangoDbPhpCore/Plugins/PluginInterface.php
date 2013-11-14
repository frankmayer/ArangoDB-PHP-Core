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
 * Interface PluginInterface
 *
 * @package frankmayer\ArangoDbPhpCore\Plugins
 */
interface  PluginInterface
{
    /**
     * @param $eventName
     * @param $client
     * @param $eventData
     *
     * @return mixed
     */
    public function notify($eventName, $client, $eventData);
}
