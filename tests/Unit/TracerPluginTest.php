<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Plugin Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Unit;


/**
 * Class PluginTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class TracerPluginTest extends TestCase
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

    // todo 1 Frank Complete tracer plugin tests


    /**
     *
     */
    public function testTracerPlugin()
    {
        // Stop here and mark this test as incomplete.
//        static::markTestIncomplete(
//            'This test has not been implemented yet.'
//        );
        static::assertTrue(true);

        //        $client = $this->getMockBuilder('frankmayer\ArangoDbPhpCore\Client')
        //                       ->disableOriginalConstructor()
        //                       ->getMock();
        //
        //        $client->method('doRequest')
        //               ->willReturn('foo');
        //        $this->client = $client;
        //        $this->client->setPluginManager(new PluginManager($this->client));
        //        static::assertInstanceOf('\frankmayer\ArangoDbPhpCore\Plugins\PluginManager', $this->client->getPluginManager());
        //
        //        $tracer            = new TestPlugin();
        //        $tracer->priority  = 0;
        //        $tracer2           = new TestPlugin();
        //        $tracer2->priority = 20;
        //        $tracer3           = new TestPlugin();
        //        $tracer3->priority = -30;
        //        $tracer4           = new TestPlugin();
        //        $tracer4->priority = 20;
        //
        //        $this->clientOptions['plugins'] = [
        //            'tracer1' => $tracer,
        //            'tracer2' => $tracer2,
        //            'tracer3' => $tracer3,
        //            'tracer4' => $tracer4,
        //        ];
        //
        //        $this->client->setPluginsFromPluginArray($this->clientOptions['plugins']);
        //        static::assertArrayHasKey('tracer3', $this->client->pluginManager->pluginStorage);
        //
        //        $e = null;
        //        try {
        //            $this->client->setPluginsFromPluginArray(['tracer5' => new \stdClass()]);
        //        } catch (\Exception $e) {
        //        }
        //        static::assertInstanceOf('\Exception', $e);
        //
        //
        //        $collection         = new Collection();
        //        $collection->client = $client;
        //
        //        /** @var $responseObject Response */
        //        $responseObject = $collection->getAll();
        //
        //        static::assertInstanceOf('frankmayer\ArangoDbPhpCore\Protocols\Http\Request', $responseObject->request);
    }


    /**
     *
     */
    public function tearDown()
    {
    }
}
