<?php
/**
 * ArangoDB PHP Core Client: Autoloader
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


/**
 * Handles automatic loading of missing class files
 * The autoloader can be nested with other autoloaders. It will only
 * process classes from its own namespace and ignore all others.
 *
 * @package   frankmayer\ArangoDbPhpCore
 */
class Autoloader
{
    /**
     * Root directory for library files
     *
     * @var string
     */
    private static $rootDir;

    /**
     * Class file extension
     */
    const EXTENSION = '.php';

    /**
     * Initialise the autoloader
     *
     * @throws Exception
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function init()
    {
        self::checkEnvironment();

        spl_autoload_register(__NAMESPACE__ . '\Autoloader::load');

        self::$rootDir = __DIR__ . '/';
    }

    /**
     * Handle loading of an unknown class
     *
     * This will only handle class from its own namespace and ignore all others.<br>
     * This allows multiple autoloaders to be used in a nested fashion.
     *
     * @param string $className - The name of class to be loaded
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function load($className)
    {
        $className = str_replace([__NAMESPACE__, "\\"], ['', DIRECTORY_SEPARATOR], $className);

        if (file_exists(self::$rootDir . $className . self::EXTENSION)) {
            require_once self::$rootDir . $className . self::EXTENSION;
        }
    }

    /**
     * Check the runtime environment
     *
     * This will check whether the runtime environment is compatible with this library
     *
     * @throws ClientException
     * @return void
     *
     * @codeCoverageIgnore
     */
    private static function checkEnvironment()
    {
        if (version_compare(PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION, '5.6.99', '<')) {
            echo PHP_MAJOR_VERSION . PHP_MINOR_VERSION . PHP_RELEASE_VERSION;
            require __DIR__ . '/ClientException.php';
            throw new ClientException('Incompatible PHP environment. Expecting PHP 7.0 or higher');
        }
    }
}
