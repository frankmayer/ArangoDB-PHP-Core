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
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpConnectorInterface;
use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;

/**
 * The Client class.
 * This is the base operating class through which everything cooperates.
 * The class provides its own IOC Container for easy injection and instantiation of needed classes.
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class Client
{
    protected static $iocContainerArray;

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

    /**
     * @param ConnectorInterface|HttpConnectorInterface $connector
     *
     * @param null                                      $clientOptions
     */
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
    public static function bind($type, \Closure $closure)
    {
        static::$iocContainerArray[$type] = $closure;
    }


    /**
     * Make method for the IOC Container
     *
     * @param $type
     *
     * @throws ClientException
     * @return mixed
     */
    public static function make($type)
    {
        try {
            $type = static::$iocContainerArray[$type];

            return $type();
        } catch (Exception $e) {
            throw new ClientException('No type registered with that name');
        }
    }
}
