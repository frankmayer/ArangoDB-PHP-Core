<?php

/**
 * ArangoDB PHP Core Client Integration Test-Suite: Plugin Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Integration;

require_once __DIR__ . '/TestCase.php';

use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\ClientOptions;
use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;
use frankmayer\ArangoDbPhpCore\Plugins\TestPlugin;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequestInterface;


/**
 * Class PluginTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class PluginTest extends TestCase
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
        $this->connector = new Connector();

        $this->setupProperties();

    }

    // todo 1 Frank Complete plugin tests

    /**
     *
     */
    public function testRegisterPluginsWithDifferentPrioritiesTestAndUnRegisterPlugin()
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
        static::assertArrayHasKey('tracer3', $this->client->pluginManager->pluginStorage);

        $e = null;
        try {
            $this->client->setPluginsFromPluginArray(['tracer5' => new \stdClass()]);
        } catch (\Exception $e) {
        }
        static::assertInstanceOf(\Exception::class, $e);

        /** @var $responseObject HttpResponse */
        $collection = new Collection($this->client);

        /** @var $responseObject HttpResponse */
        $responseObject = $collection->getAll();

        static::assertInstanceOf(HttpRequestInterface::class,
            $responseObject->request);
    }


    /**
     *
     */
    public function tearDown()
    {
    }
}
