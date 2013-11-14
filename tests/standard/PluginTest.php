<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Plugin Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140 as ArangoDbApi;

use frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector;
use frankmayer\ArangoDbPhpCore\Plugins\TracerPlugin;


class PluginTest extends
    \PHPUnit_Framework_TestCase
{
    public $clientOptions;
    public $client;


    public function setUp()
    {
        $connector    = new CurlHttpConnector();
        $this->client = getClient($connector);
    }

    // todo 1 Frank Complete plugin tests
    /**
     * Test if we can get the server version
     */
    public function testRegisterPluginsWithDifferentPrioritiesTestAndUnregisterPlugin()
    {

        $tracer            = new TracerPlugin();
        $tracer->priority  = 0;
        $tracer2           = new TracerPlugin();
        $tracer2->priority = 20;
        $tracer3           = new TracerPlugin();
        $tracer3->priority = -30;

        $this->clientOptions['plugins']                  = array(
            'tracer1' => $tracer,
            'tracer2' => $tracer2,
            'tracer3' => $tracer3
        );
        $this->clientOptions['PluginManager']['options'] = array();
        //        $this->client->traceThis();
        //        $tracer3->priority = 30;
        //        $this->client->setPluginsFromPluginArray(array('tracer3' => $tracer3));
        //        $this->client->traceThis();

    }

    public function tearDown()
    {
    }
}
