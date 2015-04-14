<?php
/**
 * ArangoDB-PHP-Core client: UpdatePolicy
 *
 * Taken from the original ArangoDB-Client, in order to maintain easy migration. Thanks Jan ;)
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Config;

use frankmayer\ArangoDbPhpCore\ClientException;

/**
 * Document update policies
 *
 * @package triagens\ArangoDb
 */
class UpdatePolicy
{
    /**
     * last update will win in case of conflicting versions
     */
    const LAST = 'last';

    /**
     * an error will be returned in case of conflicting versions
     */
    const ERROR = 'error';

    /**
     * Check if the supplied policy value is valid
     *
     * @throws ClientException
     *
     * @param string $value - update policy value
     *
     * @return void
     */
    public static function validate($value)
    {
        assert(is_string($value));

        if (!in_array($value, [self::LAST, self::ERROR])) {
            throw new ClientException('Invalid update policy');
        }
    }
}
