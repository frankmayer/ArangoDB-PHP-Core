<?php
/**
 *
 * File: ClientInterface.php
 *
 * @package
 * @author Frank Mayer
 */

namespace frankmayer\ArangoDbPhpCore;


interface ClientInterface
{
    /**
     * @param ConnectorInterface $connector
     *
     * @param null               $clientOptions
     */
    public function __construct(ConnectorInterface $connector, $clientOptions = null);


    /**
     * @param $request
     *
     * @return mixed
     */
    public function doRequest($request);


    /**
     * Binding method for the IOC Container
     *
     * @param $type
     * @param $closure
     */
    public static function bind($type, \Closure $closure);


    /**
     * Make method for the IOC Container
     *
     * @param $type
     *
     * @throws ClientException
     * @return mixed
     */
    public static function make($type);


    /**
     * @param null $plugins
     *
     * @return bool
     */
    public function setPluginsFromPluginArray($plugins = null);


    /**
     * @param       $eventName
     * @param array $eventData
     */
    public function notifyPlugins($eventName, $eventData = []);
}