<?php
/**
 * File: ClientInterface.php
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;

use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequestInterface;


/**
 * Interface ClientInterface
 *
 * @package frankmayer\ArangoDbPhpCore
 */
interface ClientInterface
{
    /**
     * @param ConnectorInterface $connector
     *
     * @param null               $clientOptions
     */
    public function __construct(ConnectorInterface $connector, $clientOptions = null);


    /**
     * @param HttpRequestInterface $request
     *
     * @return mixed
     */
    public function doRequest(HttpRequestInterface $request);


    /**
     * Binding method for the IOC Container
     *
     * @param $type
     * @param $closure
     */
    public function bind($type, \Closure $closure);


    /**
     * Make method for the IOC Container
     *
     * @param string $type
     *
     * @throws ClientException
     *
     * @return mixed
     */
    public function make(string $type);


    /**
     * @param array $plugins
     *
     * @return bool
     */
    public function setPluginsFromPluginArray(array $plugins = []);


    /**
     * @param string $eventName
     * @param array  $eventData
     */
    public function notifyPlugins(string $eventName, array $eventData = []);
}
