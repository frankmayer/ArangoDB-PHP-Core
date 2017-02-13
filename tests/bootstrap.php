<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Test Bootstrap
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Tests;

use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\ClientOptions;
use frankmayer\ArangoDbPhpCore\Plugins\TracerPlugin;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequest;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/testclasses/TestConnector.php';

function getClientOptions()
{

    //    $plugins = array('TracerPlugin' => new TracerPlugin());

    return [
        ClientOptions::OPTION_ENDPOINT             => 'http://db-link:8529',
        ClientOptions::OPTION_DEFAULT_DATABASE     => '_system',
        ClientOptions::OPTION_DATABASE_PATH_PREFIX => '/_db/',
        // endpoint to connect to
        ClientOptions::OPTION_AUTH_TYPE            => 'Basic',                 // use basic authorization
        ClientOptions::OPTION_AUTH_USER            => 'root',                      // user for basic authorization
        ClientOptions::OPTION_AUTH_PASSWD          => '',                      // password for basic authorization
        // timeout in seconds
        ClientOptions::OPTION_TIMEOUT              => 5,
        // ClientOptions::OPTION_PLUGINS              => $plugins,
        ClientOptions::OPTION_REQUEST_CLASS        => HttpRequest::class,
        ClientOptions::OPTION_RESPONSE_CLASS       => HttpResponse::class,
    ];
}


function getClient($connector)
{
    return new Client($connector, getClientOptions());
}
