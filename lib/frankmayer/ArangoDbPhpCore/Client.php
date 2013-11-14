<?php

/**
 * ArangoDB PHP Core Client: Client
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;

use frankmayer\ArangoDbPhpCore\Connectors\ConnectorInterface;
use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;

/**
 * Provides access to the ArangoDB server
 * As all access is done using HTTP, we do not need to establish a
 * persistent client and keep its state.
 * Instead, clients are established on the fly for each request
 * and are destroyed afterwards.
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class Client
{
    public $iocContainerArray;

    public $pluginManager;
    public $connector;
    public $clientOptions;
    public $database;
    public $endpoint;

    public $requestClass;
    public $responseClass;
    /**
     * @var string The ArangoDB api version to signal
     */
    public $arangodbApiVersion;

    public function __construct(ConnectorInterface $connector, $clientOptions = null)
    {
        $this->connector     = $connector;
        $this->clientOptions = $clientOptions;
        $this->endpoint      = $this->clientOptions[ClientOptions::OPTION_ENDPOINT];
        $this->database      = $this->clientOptions[ClientOptions::OPTION_DEFAULT_DATABASE];
        $this->requestClass  = $this->clientOptions[ClientOptions::OPTION_REQUEST_CLASS];
        $this->responseClass = $this->clientOptions[ClientOptions::OPTION_RESPONSE_CLASS];

        if (isset($this->clientOptions[ClientOptions::OPTION_ARANGODB_API_VERSION])) {

            $this->arangodbApiVersion = $this->clientOptions[ClientOptions::OPTION_ARANGODB_API_VERSION];
        }
        if (isset($this->clientOptions['plugins'])) {
            $this->pluginManager = new PluginManager($this, isset($this->clientOptions['plugins']) ? $this->clientOptions['plugins'] : null, isset($this->clientOptions['PluginManager']['options']) ? $this->clientOptions['PluginManager']['options'] : null);
        };
    }


    public function setPluginsFromPluginArray($plugins = null)
    {
        return $this->pluginManager->setPluginsFromPluginArray($plugins);
    }


    public function notifyPlugins($eventName, $eventData = array())
    {
        $this->pluginManager->notifyPlugins($eventName, $eventData);
    }


    //    /**
    //     * @param \frankmayer\ArangoDbPhpCore\Connectors\ConnectorInterface $connector
    //     */
    //    public function setConnector($connector)
    //    {
    //        $this->connector = $connector;
    //    }
    //
    //
    //    /**
    //     * @return \frankmayer\ArangoDbPhpCore\Connectors\ConnectorInterface
    //     */
    //    public function getConnector()
    //    {
    //        return $this->connector;
    //    }
    //
    //
    //    /**
    //     * @param mixed $database
    //     */
    //    public function setDatabase($database)
    //    {
    //        $this->database = $database;
    //    }
    //
    //
    //    /**
    //     * @return mixed
    //     */
    //    public function getDatabase()
    //    {
    //        return $this->database;
    //    }
    //
    //
    //    /**
    //     * @param string $endpoint
    //     */
    //    public function setEndpoint($endpoint)
    //    {
    //        $this->endpoint = $endpoint;
    //    }
    //
    //
    //    /**
    //     * @return string
    //     */
    //    public function getEndpoint()
    //    {
    //        return $this->endpoint;
    //    }
    //

    /**
     * @return string
     */
    public function getEndpointAndDatabasePath()
    {
        return $this->endpoint . '/_db/' . $this->database;
    }


    /**
     * @return string
     */
    public function getDatabasePath()
    {
        return '/_db/' . $this->database;
    }


    //    public function getRequest()
    //    {
    //
    //        return new $this->requestClass($this);
    //    }


    /**
     * @param $requestObject
     *
     * @return mixed
     */
    public function doRequest($requestObject)
    {
        $responseClass = $this->responseClass;

        return new $responseClass($requestObject);
    }


    /**
     * Binding method for the IOC Container
     *
     * @param $type
     * @param $closure
     */
    public function bind($type, $closure)
    {
        $this->iocContainerArray[$type] = $closure;
    }


    /**
     * Make method for the IOC Container
     *
     * @param $type
     *
     * @return mixed
     */
    public function make($type)
    {
        return $this->iocContainerArray[$type]();
    }
}
