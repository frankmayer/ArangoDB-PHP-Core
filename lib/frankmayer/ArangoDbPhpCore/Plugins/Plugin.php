<?php

/**
 * ArangoDB PHP Core Client: Abstract Plugin Class
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2015, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Plugins;


/**
 * Class Plugin
 *
 * @package frankmayer\ArangoDbPhpCore\Plugins
 */
abstract class Plugin implements
    PluginInterface
{
    /**
     * @var int $priority The priority level of the plugin-instance 0 = standard negative = lower, positive = higher
     */
    public $priority;
}