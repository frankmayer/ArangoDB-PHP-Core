<?php
/**
 *
 * File: CoreTest.php
 *
 * @package
 * @author Frank Mayer
 */
namespace frankmayer\ArangoDbPhpCore;

use frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector;
use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;
use frankmayer\ArangoDbPhpCore\Protocols\Http\Request;
use frankmayer\ArangoDbPhpCore\Protocols\Http\Response;

require_once('ArangoDbPhpCoreUnitTestCase.php');


/**
 * Class CoreTest
 * @package frankmayer\ArangoDbPhpCore
 */
class ClientUnitTest extends ArangoDbPhpCoreUnitTestCase
{
    /**
     *
     */
    public function testRequest()
    {
        //todo: write test
    }


    /**
     *
     */
    public function testIfClientInstantiable()
    {
        $connector = new Connector();
        $client    = new Client($connector);
        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Client', $client);
    }


    /**
     *
     */
    public function testSetGetArangoDBVersion()
    {
        $connector = new Connector();
        $client    = new Client($connector);
        $client->setArangoDBApiVersion('20502');
        $this->assertEquals('20502', $client->getArangoDBApiVersion());
    }


    /**
     *
     */
    public function testSetGetClientOptions()
    {
        $connector = new Connector();
        $client    = new Client($connector);
        $client->setClientOptions(['someOption' => true]);
        $this->assertEquals(['someOption' => true], $client->getClientOptions());
    }


    /**
     *
     */
    public function testSetGetConnector()
    {
        $connector1 = new Connector();
        $client     = new Client($connector1, getClientOptions());
        $connector2 = new Connector();
        $client->setConnector($connector2);
        $this->assertEquals($connector2, $client->getConnector());
    }


    /**
     *
     */
    public function testSetGetDatabase()
    {
        $connector = new Connector();
        $client    = new Client($connector, getClientOptions());
        $client->setDatabase('testDb');
        $this->assertEquals('testDb', $client->getDatabase());
    }


    /**
     *
     */
    public function testSetGetEndPoint()
    {
        $connector = new Connector();
        $client    = new Client($connector, getClientOptions());
        $client->setEndpoint('http://db-link:8529');
        $this->assertEquals('http://db-link:8529', $client->getEndpoint());
    }


    /**
     *
     */
    public function testSetGetPluginManager()
    {
        $connector = new Connector();
        $client    = new Client($connector, getClientOptions());
        $pluginManager = new PluginManager($client);
        $client->setPluginManager($pluginManager);
        $this->assertEquals($pluginManager, $client->getPluginManager());
    }


    /**
     *
     */
    public function testSetGetRequestClass()
    {
        $connector    = new Connector();
        $client       = new Client($connector, getClientOptions());
        $requestClass = new Request();
        $client->setRequestClass($requestClass);
        $this->assertEquals($requestClass, $client->getRequestClass());
    }


    /**
     *
     */
    public function testSetGetResponseClass()
    {
        $connector     = new Connector();
        $client        = new Client($connector, getClientOptions());
        $responseClass = new Response();
        $client->setResponseClass($responseClass);
        $this->assertEquals($responseClass, $client->getResponseClass());
    }
}