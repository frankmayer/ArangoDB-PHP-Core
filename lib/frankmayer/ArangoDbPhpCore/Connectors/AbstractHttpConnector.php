<?php
/**
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors;


use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpConnectorInterface;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequestInterface;

/**
 * Class AbstractHttpConnector
 *
 * @package frankmayer\ArangoDbPhpCore\Connectors
 */
abstract class AbstractHttpConnector implements HttpConnectorInterface
{

    const HTTP_EOL = "\r\n";

    /**
     * @var bool switch for turning on curl verbose logging
     */
    protected $verboseLogging;

    /**
     * @var Client The client object in which this connector is used.
     */
    public $client;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->verboseLogging = false;
    }

    /**
     * @param HttpRequestInterface $request
     *
     * @return mixed
     */
    public abstract function send(HttpRequestInterface $request);

    /**
     * @param boolean $verbose
     *
     * @return $this
     */
    public function setVerboseLogging($verbose)
    {
        $this->verboseLogging = $verbose;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getVerboseLogging()
    {
        return $this->verboseLogging;
    }
}
