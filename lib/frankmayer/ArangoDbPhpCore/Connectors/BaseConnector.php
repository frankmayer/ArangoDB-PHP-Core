<?php
/**
 *
 * File: BaseConnector.php
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors;


use frankmayer\ArangoDbPhpCore\Protocols\Http\ConnectorInterface;

abstract class BaseConnector implements
    ConnectorInterface
{

    const HTTP_EOL = "\r\n";

    /**
     * @var bool switch for turning on curl verbose logging
     */
    protected $verboseLogging;
    /**
     * @var null The client object in which this connector is used.
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
     * @param boolean $verbose
     */
    public function setVerboseLogging($verbose)
    {
        $this->verboseLogging = $verbose;
    }

    /**
     * @return boolean
     */
    public function getVerboseLogging()
    {
        return $this->verboseLogging;
    }
}