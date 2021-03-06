<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Plugin Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Unit;

require_once __DIR__ . '/TestCase.php';

use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;
use frankmayer\ArangoDbPhpCore\Plugins\TestPlugin;


/**
 * Class PluginTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class PluginTest extends TestCase
{
    /**
     * @var Client $client
     */
    protected $connector;


    /**
     *
     */
    public function setUp()
    {

        $this->connector = $this->getMockBuilder(\TestConnector::class)
            ->getMock();

        $this->setupProperties();
    }


    public function testRegisterPluginsAndUnRegisterPlugins()
    {
        $this->client->setPluginManager(new PluginManager($this->client));
        static::assertInstanceOf(PluginManager::class, $this->client->getPluginManager());

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
        static::assertArrayHasKey('tracer1', $this->client->pluginManager->pluginStorage);
        static::assertArrayHasKey('tracer2', $this->client->pluginManager->pluginStorage);
        static::assertArrayHasKey('tracer3', $this->client->pluginManager->pluginStorage);
        static::assertArrayHasKey('tracer4', $this->client->pluginManager->pluginStorage);

        $keys = array_keys($this->client->pluginManager->pluginStorage);
        static::assertSame($this->client->pluginManager->pluginStorage[$keys[2]],
            $this->client->pluginManager->pluginStorage['tracer1']);
        static::assertSame($this->client->pluginManager->pluginStorage[$keys[3]],
            $this->client->pluginManager->pluginStorage['tracer3']);


        $this->client->pluginManager->removePluginInstance('tracer1');
        $this->client->pluginManager->removePluginInstance('tracer2');
        $this->client->pluginManager->removePluginInstance('tracer3');
        $this->client->pluginManager->removePluginInstance('tracer4');

        static::assertArrayNotHasKey('tracer1', $this->client->pluginManager->pluginStorage);
        static::assertArrayNotHasKey('tracer2', $this->client->pluginManager->pluginStorage);
        static::assertArrayNotHasKey('tracer3', $this->client->pluginManager->pluginStorage);
        static::assertArrayNotHasKey('tracer4', $this->client->pluginManager->pluginStorage);
    }


    public function testFailureWhenRegisteringNonPlugin()
    {
        $this->client->setPluginManager(new PluginManager($this->client));

        $e = null;
        try {
            $this->client->setPluginsFromPluginArray(['tracer5' => new \stdClass()]);
        } catch (\Exception $e) {
        }
        static::assertInstanceOf(\Exception::class, $e);
    }


    /**
     *
     */
    public function tearDown()
    {
    }
}
