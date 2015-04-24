<?php

/**
 * ArangoDB PHP Core Client: Basic Tracer Plugin
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
class TracerPlugin extends
    Plugin
{
    /**
     * @param $eventName
     * @param $client
     * @param $eventData
     *
     * @return mixed|void
     */
    // Ignoring this for now, as we will revisit this later...
    // @codeCoverageIgnoreStart
    public function notify($eventName, $client, $eventData)
    {
        // todo 1 Frank Make basic tracer plugin
        echo "tracing event (" . ($this->priority) . "):", $eventName, PHP_EOL;
    }
    // @codeCoverageIgnoreEnd
}
