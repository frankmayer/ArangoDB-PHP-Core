<?php
/**
 *
 * File: CoreTest.php
 *
 * @package
 * @author Frank Mayer
 */
namespace frankmayer\ArangoDbPhpCore;

use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequest;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse;

require_once 'ArangoDbPhpCoreUnitTestCase.php';


/**
 * Class CoreTest
 * @package frankmayer\ArangoDbPhpCore
 */
class ClientUnitTest extends ArangoDbPhpCoreUnitTestCase
{
    private $connector;
    private $connector2;


    public function setup()
    {
        $this->connector  = $this->getMockBuilder('TestConnector')
                                 ->getMock();
        $this->connector2 = $this->getMockBuilder('TestConnector')
                                 ->getMock();
    }


    /**
     *
     */
    public function testIfClientInstantiable()
    {
        $client = new Client($this->connector);
        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Client', $client);
    }


    /**
     *
     */
    public function testSetGetClientOptions()
    {
        $client = new Client($this->connector);
        $client->setClientOptions(['someOption' => true]);
        $this->assertEquals(['someOption' => true], $client->getClientOptions());
    }


    /**
     *
     */
    public function testSetGetConnector()
    {
        $client = new Client($this->connector, getClientOptions());

        $client->setConnector($this->connector2);
        $this->assertEquals($this->connector2, $client->getConnector());
    }


    /**
     *
     */
    public function testSetGetDatabase()
    {
        $client = new Client($this->connector, getClientOptions());
        $client->setDatabase('testDb');
        $this->assertEquals('testDb', $client->getDatabase());
    }


    /**
     *
     */
    public function testSetGetEndPoint()
    {
        $client = new Client($this->connector, getClientOptions());
        $client->setEndpoint('http://db-link:8529');
        $this->assertEquals('http://db-link:8529', $client->getEndpoint());
    }


    /**
     *
     */
    public function testSetGetPluginManager()
    {
        $client        = new Client($this->connector, getClientOptions());
        $pluginManager = new PluginManager($client);
        $client->setPluginManager($pluginManager);
        $this->assertEquals($pluginManager, $client->getPluginManager());
    }


    /**
     *
     */
    public function testSetGetRequestClass()
    {
        $client       = new Client($this->connector, getClientOptions());
        $requestClass = new HttpRequest($client);
        $client->setRequestClass($requestClass);
        $this->assertEquals($requestClass, $client->getRequestClass());
    }


    /**
     *
     */
    public function testSetGetResponseClass()
    {
        $client        = new Client($this->connector, getClientOptions());
        $responseClass = new HttpResponse();
        $client->setResponseClass($responseClass);
        $this->assertEquals($responseClass, $client->getResponseClass());
    }
}
