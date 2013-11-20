<?php

/**
 * ArangoDB PHP Core Client: Plugin Manager Class
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Plugins;

use frankmayer\ArangoDbPhpCore\ClientException;


/**
 * Class PluginManager
 *
 * @package frankmayer\ArangoDbPhpCore\Plugins
 */
class PluginManager
{
    /**
     * @var array
     */
    public $pluginStorage;
    /**
     * @var
     */
    public $client;
    /**
     * @var array
     */
    public $options;


    /**
     * @param       $client
     * @param array $plugins
     * @param array $options
     */
    public function __construct($client, $plugins = array(), $options = array('notificationsEnabled' => true))
    {
        $options['notificationsEnabled'] = true;

        $this->client        = $client;
        $this->pluginStorage = array();
        $this->options       = $options;
        $this->setPluginsFromPluginArray($plugins);
    }


    /**
     * @param null $plugins
     *
     * @return bool
     * @throws \frankmayer\ArangoDbPhpCore\ClientException
     */
    public function setPluginsFromPluginArray($plugins = null)
    {
        if (is_array($plugins) && count($plugins) > 0) {
            foreach ($plugins as $key => $plugin) {
                if (is_subclass_of($plugin, 'frankmayer\ArangoDbPhpCore\Plugins\Plugin')) {
                    $this->pluginStorage[$key]['plugin']   = $plugin;
                    $this->pluginStorage[$key]['priority'] = $plugin->priority;
                } else {
                    throw new ClientException('Could not initialize plugin. Is not a subclass of frankmayer\ArangoDbPhpCore\Plugins\PluginPlugin');
                }
            }
        }
        uksort($this->pluginStorage, array($this, 'comparePluginPriorities'));

        return true;
    }


    /**
     * @param       $eventName
     * @param array $eventData
     */
    public function notifyPlugins($eventName, $eventData = array())
    {
        if ($this->options['notificationsEnabled'] === true) {
            if (count($this->pluginStorage) > 0) {
                foreach ($this->pluginStorage as $key => $priority) {
                    $plugin = $this->pluginStorage[$key]['plugin'];
                    /** @var $plugin Plugin */
                    $plugin->notify($eventName, $this->client, $eventData);
                }
            }
        }
    }


    /**
     * @param $a
     * @param $b
     *
     * @return int
     */
    function comparePluginPriorities($a, $b)
    {
        if ($this->pluginStorage[$a]['priority'] === $this->pluginStorage[$b]['priority']) {
            return 0;
        }

        return ($this->pluginStorage[$a]['priority'] > $this->pluginStorage[$b]['priority']) ? -1 : 1;
    }
}