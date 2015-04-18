<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Plugin Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;

require_once('ArangoDbPhpCoreIntegrationTestCase.php');

use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector;
use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;
use frankmayer\ArangoDbPhpCore\Plugins\TestPlugin;
use frankmayer\ArangoDbPhpCore\Protocols\Http\Response;


/**
 * Class PluginTest
 * @package frankmayer\ArangoDbPhpCore
 */
class PluginTest extends ArangoDbPhpCoreIntegrationTestCase
{
    /**
     * @var ClientOptions $clientOptions
     */
    public $clientOptions;

    /**
     * @var Client $client
     */
    public $client;


    /**
     *
     */
    public function setUp()
    {
        $connector    = new Connector();
        $this->client = getClient($connector);
    }

    // todo 1 Frank Complete plugin tests

    /**
     *
     */
    public function testRegisterPluginsWithDifferentPrioritiesTestAndUnRegisterPlugin()
    {
        $this->client->setPluginManager(new PluginManager($this->client));
        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Plugins\PluginManager', $this->client->getPluginManager());

        $tracer            = new TestPlugin();
        $tracer->priority  = 0;
        $tracer2           = new TestPlugin();
        $tracer2->priority = 20;
        $tracer3           = new TestPlugin();
        $tracer3->priority = -30;
        $tracer4           = new TestPlugin();
        $tracer4->priority = 20;

        $this->clientOptions['plugins'] = [
            'tracer1' => $tracer,
            'tracer2' => $tracer2,
            'tracer3' => $tracer3,
            'tracer4' => $tracer4,
        ];

        $this->client->setPluginsFromPluginArray($this->clientOptions['plugins']);
        $this->assertArrayHasKey('tracer3', $this->client->pluginManager->pluginStorage);

        $e = null;
        try {
            $this->client->setPluginsFromPluginArray(['tracer5' => new \stdClass()]);
        } catch (\Exception $e) {
        }
        $this->assertInstanceOf('\Exception', $e);

        /** @var $responseObject Response */
        $responseObject = Collection::getAll($this->client);

        $this->assertInstanceOf('frankmayer\ArangoDbPhpCore\Protocols\Http\Request', $responseObject->request);
    }


    /**
     *
     */
    public function tearDown()
    {
    }
}