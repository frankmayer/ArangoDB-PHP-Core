<?php

/**
 * ArangoDB PHP Core Client: Abstract Plugin Class
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
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
     * @var
     */
    public $priority;
}