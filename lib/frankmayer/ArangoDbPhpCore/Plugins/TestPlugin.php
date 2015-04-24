<?php

/**
 * ArangoDB PHP Core Client: Basic Test Plugin
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Plugins;


/**
 * Class TracerPlugin
 *
 * @package frankmayer\ArangoDbPhpCore\Plugins
 */
class TestPlugin extends
    Plugin
{
    /**
     * @param $eventName
     * @param $client
     * @param $eventData
     *
     * @return mixed|void
     */
    public function notify($eventName, $client, $eventData)
    {
        //        echo "tracing event (".($this->priority)."):",$eventName,PHP_EOL;
        $eventData->pluginTest = 'plugin tested';
    }
}
