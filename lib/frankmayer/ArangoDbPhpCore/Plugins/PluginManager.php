<?php

/**
 * ArangoDB PHP Core Client: Plugin Manager Class
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2015, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Plugins;

use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\ClientException;


/**
 * Class PluginManager
 *
 * @package frankmayer\ArangoDbPhpCore\Plugins
 */
class PluginManager
{
    /**
     * @var array $pluginStorage The Storgate Array for the plugin instances. The array is already in the correct order.
     */
    public $pluginStorage;
    /**
     * @var Client $client The instance of the client, for which this plugin-manager is doing its job.
     */
    public $client;
    /**
     * @var array $options Options passed to the plugin manager.
     */
    public $options;


	/**
	 * @param       $client
	 * @param array $plugins
	 * @param array $options
	 *
	 * @throws \frankmayer\ArangoDbPhpCore\ClientException
	 */
    public function __construct($client, array $plugins = [], array $options = [])
    {
        $options['notificationsEnabled'] = true;

        $this->client        = $client;
        $this->pluginStorage = [];
        $this->options       = $options;
        $this->setPluginsFromPluginArray($plugins);
    }


	/**
	 * @param array $plugins
	 *
	 * @return bool
	 * @throws ClientException
	 */
    public function setPluginsFromPluginArray(array $plugins = [])
    {
        if (count($plugins) > 0) {
            foreach ($plugins as $key => $plugin) {
                if (is_subclass_of($plugin, 'frankmayer\ArangoDbPhpCore\Plugins\Plugin')) {
                    $this->pluginStorage[$key]['plugin']   = $plugin;
                    $this->pluginStorage[$key]['priority'] = $plugin->priority;
                } else {
                    throw new ClientException('Could not initialize plugin. Is not a subclass of frankmayer\ArangoDbPhpCore\Plugins\PluginPlugin');
                }
            }
        }
        uksort($this->pluginStorage, [$this, 'comparePluginPriorities']);

        return true;
    }


    /**
     * @param       $eventName
     * @param array $eventData
     */
    public function notifyPlugins($eventName, array $eventData = [])
    {
        if ($this->options['notificationsEnabled'] === true && count($this->pluginStorage) > 0) {
            foreach ($this->pluginStorage as $key => $priority) {
                $plugin = $this->pluginStorage[$key]['plugin'];
                /** @var $plugin Plugin */
                $plugin->notify($eventName, $this->client, $eventData);
            }
        }
    }


    /**
     * @param $a
     * @param $b
     *
     * @return int
     */
    protected function comparePluginPriorities($a, $b)
    {
        if ($this->pluginStorage[$a]['priority'] === $this->pluginStorage[$b]['priority']) {
            return 0;
        }

        return ($this->pluginStorage[$a]['priority'] > $this->pluginStorage[$b]['priority']) ? -1 : 1;
    }

    /**
     * @param $instanceName
     */
    public function removePluginInstance($instanceName)
    {
        unset ($this->pluginStorage[$instanceName]);
    }
}