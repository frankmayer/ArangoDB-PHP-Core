<?php
/**
 * ArangoDB PHP Core Client: Client
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;

use frankmayer\ArangoDbPhpCore\Connectors\AbstractHttpConnector;
use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;
use frankmayer\ArangoDbPhpCore\Protocols\Http\AbstractHttpRequest;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequestInterface;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponseInterface;


/**
 * The Client class.
 * This is the base operating class through which everything cooperates.
 * The class provides its own IOC Container for easy injection and instantiation of needed classes.
 *
 * @package   frankmayer\ArangoDbPhpCore
 */
class Client implements ClientInterface
{
    protected $iocContainerArray;

    /**
     * @var Plugins\PluginManager The plugin-manager for this client.
     */
    public $pluginManager;
    /**
     * @var ConnectorInterface $connector The connector instance to use, in order to connect to the server
     */
    public $connector;
    /**
     * @var array The client options. An array of client options.
     */
    public $clientOptions;
    /**
     * @var string The database path prefix to use. For example: '/_db/'. (defaults to '/_db/')
     */
    public $databasePathPrefix;
    /**
     * @var string The database to use. For example: 'my_database'
     */
    public $database;
    /**
     * @var string The the full database path. This is a concatenation of $databasePathPrefix & $database that happens on instantiation.
     */
    public $fullDatabasePath;
    /**
     * @var string The endpoint to connect to. For example: 'http://localhost:8529'
     */
    public $endpoint;
    /**
     * @var string The class name (including path) for example 'frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest'
     */
    public $requestClass;

    /**
     * @var string The class name (including path) for example 'frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse'
     */
    public $responseClass;


    /**
     * @param ConnectorInterface|AbstractHttpConnector $connector
     *
     * @param array                                    $clientOptions
     *
     * @throws \frankmayer\ArangoDbPhpCore\ClientException
     */
    public function __construct(ConnectorInterface $connector, $clientOptions = null)
    {
        $this->connector          = $connector;
        $this->connector->client  = $this;
        $this->clientOptions      = $clientOptions;
        $this->endpoint           = $this->clientOptions[ClientOptions::OPTION_ENDPOINT];
        $this->databasePathPrefix = $this->clientOptions[ClientOptions::OPTION_DATABASE_PATH_PREFIX];
        $this->database           = $this->clientOptions[ClientOptions::OPTION_DEFAULT_DATABASE];
        $this->fullDatabasePath   = $this->databasePathPrefix . $this->database;

        $this->requestClass  = $this->clientOptions[ClientOptions::OPTION_REQUEST_CLASS];
        $this->responseClass = $this->clientOptions[ClientOptions::OPTION_RESPONSE_CLASS];

        if (isset($this->clientOptions['plugins'])) {
            $this->pluginManager = new PluginManager($this,
                $this->clientOptions['plugins'] ?? null,
                $this->clientOptions['PluginManager']['options'] ?? null);
        }
    }


    /**
     * @param array $plugins
     *
     * @return bool
     * @throws ClientException
     */
    public function setPluginsFromPluginArray(array $plugins = [])
    {
        return $this->pluginManager->setPluginsFromPluginArray($plugins);
    }


    /**
     * @param string $eventName
     * @param array  $eventData
     */
    public function notifyPlugins(string $eventName, array $eventData = [])
    {
        if ($this->pluginManager) {
            $this->pluginManager->notifyPlugins($eventName, $eventData);
        }
    }


    /**
     * @param HttpRequestInterface $request
     *
     * @return mixed
     */
    public function doRequest(HttpRequestInterface $request)
    {
        $responseClass = $this->responseClass;

        /** @var HttpResponseInterface $responseObject */
        $responseObject = new $responseClass();

        return $responseObject->build($request);
    }


    /**
     * Binding method for the IOC Container
     *
     * @param $type
     * @param $closure
     */
    public function bind($type, \Closure $closure)
    {
        $this->iocContainerArray[$type] = $closure;
    }


    /**
     * Make method for the IOC Container
     *
     * @param string $type
     *
     * @throws ClientException
     * @return mixed
     */
    public function make(string $type)
    {
        if (isset($this->iocContainerArray[$type])) {
            $type = $this->iocContainerArray[$type];

            return $type();
        }
        throw new ClientException('No type registered with that name');
    }


    /**
     * Set complete Client Options
     *
     * @param array $clientOptions
     *
     * @return $this
     */
    public function setClientOptions($clientOptions)
    {
        $this->clientOptions = $clientOptions;

        return $this;
    }


    /**
     * @return array
     */
    public function getClientOptions()
    {
        return $this->clientOptions;
    }


    /**
     * @param \frankmayer\ArangoDbPhpCore\ConnectorInterface $connector
     *
     * @return $this
     */
    public function setConnector(ConnectorInterface $connector)
    {
        $this->connector = $connector;

        return $this;
    }


    /**
     * @return \frankmayer\ArangoDbPhpCore\ConnectorInterface
     */
    public function getConnector()
    {
        return $this->connector;
    }


    /**
     * @param string $database
     *
     * @return $this
     */
    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }


    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }


    /**
     * @param string $endpoint
     *
     * @return $this
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }


    /**
     * @return string
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


    /**
     * @param RequestInterface|HttpRequestInterface $requestClass
     *
     * @return $this
     */
    public function setRequestClass($requestClass)
    {
        $this->requestClass = $requestClass;

        return $this;
    }


    /**
     * @return RequestInterface
     */
    public function getRequestClass()
    {
        return $this->requestClass;
    }


    /**
     * @return AbstractHttpRequest
     */
    public function getRequest()
    {
        return new $this->requestClass($this);
    }


    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return new $this->responseClass($this);
    }


    /**
     * @param ResponseInterface $responseClass
     *
     * @return $this
     */
    public function setResponseClass($responseClass)
    {
        $this->responseClass = $responseClass;

        return $this;
    }


    /**
     * @return ResponseInterface
     */
    public function getResponseClass()
    {
        return $this->responseClass;
    }
}
