<?php

/**
 * ArangoDB PHP Core Client: Client
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;

use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;
use frankmayer\ArangoDbPhpCore\TraitRepository\ClientLogger;

/**
 * Provides access to the ArangoDB server
 * As all access is done using HTTP, we do not need to establish a
 * persistent client and keep its state.
 * Instead, clients are established on the fly for each request
 * and are destroyed afterwards.
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class TraitClient
{
    protected $pluginManager;
    protected $connector;
    protected $clientOptions;
    protected $database;
    protected $endpoint;

    protected $requestClass;
    protected $responseClass;

    protected $request;
    protected $response;

    use ClientLogger;


    public function __construct(ConnectorInterface $connector, $clientOptions = null)
    {
        //        $clientOptions['PluginManager']['options']['notificationsEnabled'] = false;
        $this->endpoint      = $clientOptions[ClientOptions::OPTION_ENDPOINT];
        $this->database      = $clientOptions[ClientOptions::OPTION_DEFAULT_DATABASE];
        $this->pluginManager = new PluginManager($this,
            $clientOptions['plugins'] ?? null,
            $clientOptions['PluginManager']['options'] ?? null);
        $this->connector     = $connector;
        $this->clientOptions = $clientOptions;
        $this->requestClass  = 'frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest';
        $this->responseClass = 'frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse';
    }

    public function setPluginsFromPluginArray($plugins = null)
    {
        return $this->pluginManager->setPluginsFromPluginArray($plugins);
    }

    //        public function logThis()
    //        {
    ////            echo $this->log('Trait WORKS!!!!');
    ////
    ////            $test1 = 1;
    ////            $this->notifyPlugins('core.client.traceThis', array('test1' => &$test1));
    //        }

    public function notifyPlugins($eventName, array $eventData = [])
    {
        $this->pluginManager->notifyPlugins($eventName, $eventData);
    }


    /**
     * @param \frankmayer\ArangoDbPhpCore\ConnectorInterface $connector
     */
    public function setConnector($connector)
    {
        $this->connector = $connector;
    }

    /**
     * @return \frankmayer\ArangoDbPhpCore\ConnectorInterface
     */
    public function getConnector()
    {
        return $this->connector;
    }

    /**
     * @param mixed $database
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
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

    public function createCollection()
    {
    }


    //        $container->response = function ($client) {
    //            return new HttpResponse($client);
    //
    //        };
    //        $container->request  = function ($client) {
    //            return new HttpRequest($client);
    //        };

    public function getRequest()
    {

        return new $this->requestClass($this);
    }

    public function getResponse($requestObject)
    {
        $responseClass = $this->responseClass;

        return new $responseClass($requestObject);
    }
}
