<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Test Bootstrap
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


require dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';


function getClientOptions()
{
    //$plugins = array();

    return array(
        ClientOptions::OPTION_ENDPOINT         => 'http://localhost:8529',
        ClientOptions::OPTION_DEFAULT_DATABASE => '_system',
        // endpoint to connect to
        /*
        ClientOptions::OPTION_AUTH_TYPE       => 'Basic',                 // use basic authorization
        ClientOptions::OPTION_AUTH_USER       => '',                      // user for basic authorization
        ClientOptions::OPTION_AUTH_PASSWD     => '',                      // password for basic authorization
        */
        ClientOptions::OPTION_TIMEOUT          => 5,
        // timeout in seconds
        // ClientOptions::OPTION_TRACE           => $traceFunc,              // tracer function, can be used for debugging
        // ClientOptions::OPTION_PLUGINS => $plugins,
        ClientOptions::OPTION_REQUEST_CLASS    => 'frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest',
        ClientOptions::OPTION_RESPONSE_CLASS   => 'frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse',
    );
}


function getClient($connector)
{
    return new Client($connector, getClientOptions());
}

