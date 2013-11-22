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
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse;
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

    /**
     * @var Plugins\PluginManager
     */
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
        if ($this->pluginManager) {
            $this->pluginManager->notifyPlugins($eventName, $eventData);
        }
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
        $response      = new $responseClass();

        $response->request = $requestObject;
        /** @var $response HttpResponse */
        $response->doConstruct();

        return $response;
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
        if (isset(static::$iocContainerArray[$type])) {
            $type = static::$iocContainerArray[$type];

            return $type();
        }
        throw new ClientException('No type registered with that name');
    }

    /**
     * @param string $arangodbApiVersion
     *
     * @return $this
     */
    public function setArangodbApiVersion($arangodbApiVersion)
    {
        $this->arangodbApiVersion = $arangodbApiVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getArangodbApiVersion()
    {
        return $this->arangodbApiVersion;
    }

    /**
     * @param null $clientOptions
     *
     * @return $this
     */
    public function setClientOptions($clientOptions)
    {
        $this->clientOptions = $clientOptions;

        return $this;
    }

    /**
     * @return null
     */
    public function getClientOptions()
    {
        return $this->clientOptions;
    }

    /**
     * @param \frankmayer\ArangoDbPhpCore\Connectors\ConnectorInterface|\frankmayer\ArangoDbPhpCore\Connectors\Http\HttpConnectorInterface $connector
     *
     * @return $this
     */
    public function setConnector(\frankmayer\ArangoDbPhpCore\Connectors\ConnectorInterface $connector)
    {
        $this->connector = $connector;

        return $this;
    }

    /**
     * @return \frankmayer\ArangoDbPhpCore\Connectors\ConnectorInterface|\frankmayer\ArangoDbPhpCore\Connectors\Http\HttpConnectorInterface
     */
    public function getConnector()
    {
        return $this->connector;
    }

    /**
     * @param mixed $database
     *
     * @return $this
     */
    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param mixed $endpoint
     *
     * @return $this
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }


    /**
     * @param \frankmayer\ArangoDbPhpCore\Plugins\PluginManager $pluginManager
     *
     * @return $this
     */
    public function setPluginManager($pluginManager)
    {
        $this->pluginManager = $pluginManager;

        return $this;
    }

    /**
     * @return \frankmayer\ArangoDbPhpCore\Plugins\PluginManager
     */
    public function getPluginManager()
    {
        return $this->pluginManager;
    }

    // todo 1 Frank Rework request/response classes configuration

    /**
     * @param mixed $requestClass
     *
     * @return $this
     */
    public function setRequestClass($requestClass)
    {
        $this->requestClass = $requestClass;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestClass()
    {
        return $this->requestClass;
    }

    /**
     * @param mixed $responseClass
     *
     * @return $this
     */
    public function setResponseClass($responseClass)
    {
        $this->responseClass = $responseClass;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponseClass()
    {
        return $this->responseClass;
    }
}
