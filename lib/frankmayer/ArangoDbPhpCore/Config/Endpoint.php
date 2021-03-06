<?php

/**
 * ArangoDB-PHP-Core client: Endpoint configuration
 *
 * Taken from the original ArangoDB-Client, in order to maintain easy migration. Thanks Jan ;)
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */
// todo 1 Frank Revisit Endpoints Not sure how to handle this in the core level...
namespace frankmayer\ArangoDbPhpCore\Config;

use frankmayer\ArangoDbPhpCore\ClientException;

/**
 * Endpoint specification
 *
 * An endpoint contains the server location the client connects to
 * the following endpoint types are currently supported (more to be added later):
 * - tcp://host:port for tcp connections
 * - unix://socket for UNIX sockets (provided the server supports this)
 * - ssl://host:port for SSL connections (provided the server supports this)
 *
 * Note: SSL support is added in ArangoDB server 1.1
 *
 * @package   frankmayer\ArangoDbPhpCore\Config
 */
class Endpoint
{
    /**
     * Current endpoint value
     *
     * @var string
     */
    private $_value;

    /**
     * TCP endpoint type
     */
    const TYPE_TCP = 'tcp';

    /**
     * SSL endpoint type
     */
    const TYPE_SSL = 'ssl';

    /**
     * UNIX socket endpoint type
     */
    const TYPE_UNIX = 'unix';

    /**
     * Regexp for TCP endpoints
     */
    const REGEXP_TCP = '/^tcp:\/\/(.+?):(\d+)\/?$/';

    /**
     * Regexp for SSL endpoints
     */
    const REGEXP_SSL = '/^ssl:\/\/(.+?):(\d+)\/?$/';

    /**
     * Regexp for UNIX socket endpoints
     */
    const REGEXP_UNIX = '/^unix:\/\/(.+)$/';

    /**
     * Create a new endpoint
     *
     * @param string $value - endpoint specification
     *
     * @throws ClientException
     */
    public function __construct($value)
    {
        if (!self::isValid($value)) {
            throw new ClientException(sprintf("invalid endpoint specification '%s'", $value));
        }

        $this->_value = $value;
    }

    /**
     * Return a string representation of the endpoint
     *
     * @return string - string representation of the endpoint
     */
    public function __toString()
    {
        return $this->_value;
    }

    /**
     * Return the type of an endpoint
     *
     * @param string $value - endpoint specification value
     *
     * @return string - endpoint type
     */
    public static function getType($value)
    {
        if (preg_match(self::REGEXP_TCP, $value)) {
            return self::TYPE_TCP;
        }

        if (preg_match(self::REGEXP_SSL, $value)) {
            return self::TYPE_SSL;
        }

        if (preg_match(self::REGEXP_UNIX, $value)) {
            return self::TYPE_UNIX;
        }

        return null;
    }

    /**
     * Return the host name of an endpoint
     *
     * @param string $value - endpoint specification value
     *
     * @return string - host name
     */
    public static function getHost($value)
    {
        if (preg_match(self::REGEXP_TCP, $value, $matches)) {
            return $matches[1];
        }

        if (preg_match(self::REGEXP_SSL, $value, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * check whether an endpoint specification is valid
     *
     * @param string $value - endpoint specification value
     *
     * @return bool - true if endpoint specification is valid, false otherwise
     */
    public static function isValid($value)
    {
        if (!is_string($value)) {
            return false;
        }

        $type = self::getType($value);

        return !($type === null);
    }
}
