<?php
/**
 * /**
 * ArangoDB-PHP-Core client: client options
 *
 * Taken from the original ArangoDB-Client, in order to maintain easy migration. Thanks Jan ;)
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Config;

return array(
    ClientOptions::OPTION_ENDPOINT      => 'tcp://localhost:8529/',
    // endpoint to connect to
    ClientOptions::OPTION_CLIENT        => 'Close',
    // can use either 'Close' (one-time clients) or 'Keep-Alive' (re-used clients)
    ClientOptions::OPTION_AUTH_TYPE     => 'Basic',
    // use basic authorization
    /*
    ClientOptions::OPTION_AUTH_USER       => '',                      // user for basic authorization
    ClientOptions::OPTION_AUTH_PASSWD     => '',                      // password for basic authorization
    ClientOptions::OPTION_PORT            => 8529,                    // port to connect to (deprecated, should use endpoint instead)
    ClientOptions::OPTION_HOST            => "localhost",             // host to connect to (deprecated, should use endpoint instead)
    */
    ClientOptions::OPTION_TIMEOUT       => 5,
    // timeout in seconds
    //ClientOptions::OPTION_TRACE           => $traceFunc,              // tracer function, can be used for debugging
    ClientOptions::OPTION_CREATE        => false,
    // do not create unknown collections automatically
    ClientOptions::OPTION_UPDATE_POLICY => UpdatePolicy::LAST,
    // last update wins
    ClientOptions::OPTION_UPDATE_POLICY => array(
        ClientOptions::OPTION_TIMEOUT => 5,
    )
);