<?php
/**
 * ArangoDB-PHP-Core client: client options
 *
 * Taken from the original ArangoDB-Client, in order to maintain easy migration. Thanks Jan ;)
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Config;

use frankmayer\ArangoDbPhpCore\ClientOptions;

return [
    // endpoint to connect to
    ClientOptions::OPTION_ENDPOINT      => 'tcp://localhost:8529/',
    // can use either 'Close' (one-time clients) or 'Keep-Alive' (re-used clients)
    ClientOptions::OPTION_CLIENT        => 'Close',
    // use basic authorization
    ClientOptions::OPTION_AUTH_TYPE     => 'Basic',
    /*
    ClientOptions::OPTION_AUTH_USER       => '',                      // user for basic authorization
    ClientOptions::OPTION_AUTH_PASSWD     => '',                      // password for basic authorization
    ClientOptions::OPTION_PORT            => 8529,                    // port to connect to (deprecated, should use endpoint instead)
    ClientOptions::OPTION_HOST            => "localhost",             // host to connect to (deprecated, should use endpoint instead)
    */
    // timeout in seconds
    ClientOptions::OPTION_TIMEOUT       => 5,
    //ClientOptions::OPTION_TRACE           => $traceFunc,              // tracer function, can be used for debugging

    // do not create unknown collections automatically
    ClientOptions::OPTION_CREATE        => false,
    // last update wins
    ClientOptions::OPTION_UPDATE_POLICY => UpdatePolicy::LAST,
];