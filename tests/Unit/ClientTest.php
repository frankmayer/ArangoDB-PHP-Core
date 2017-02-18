<?php
/**
 *
 * File: CoreTest.php
 *
 * @package
 * @author Frank Mayer
 */
namespace frankmayer\ArangoDbPhpCore\Tests\Unit;

use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\ClientException;
use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequest;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse;

require_once __DIR__ . '/TestCase.php';


/**
 * Class CoreTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class ClientTest extends TestCase
{
    protected $connector;
    protected $connector2;


    public function setup()
    {

        $this->connector  = $this->getMockBuilder(\TestConnector::class)
            ->getMock();
        $this->connector2 = $this->getMockBuilder(\TestConnector::class)
            ->getMock();

        $this->connector = new \TestConnector();

        $this->setupProperties();
    }

    public function testBindClassToTypeAndMake(){
        $this->client->bind(
            'Request',
            function () {
                return $this->client->getRequest();
            }
        );
        $request          = $this->client->make('Request');
        static::assertInstanceOf('\frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequest', $request);

    }

    public function testMakeNonExistentClassType()
    {
        try{
            $request          = $this->client->make('Some FakeRequestClass');
        }catch (ClientException $e){

        }
        static::assertInstanceOf('\frankmayer\ArangoDbPhpCore\ClientException', $e);

    }

    /**
     *
     */
    public function testIfClientInstantiable()
    {
        $client = new Client($this->connector);
        static::assertInstanceOf(Client::class, $client);
    }


    /**
     *
     */
    public function testSetGetClientOptions()
    {
        $client = new Client($this->connector);
        $client->setClientOptions(['someOption' => true]);
        static::assertEquals(['someOption' => true], $client->getClientOptions());
    }


    /**
     *
     */
    public function testSetGetConnector()
    {
        $client = new Client($this->connector, $this->clientOptions);

        $client->setConnector($this->connector2);
        static::assertEquals($this->connector2, $client->getConnector());
    }


    /**
     *
     */
    public function testSetGetDatabase()
    {
        $client = new Client($this->connector, $this->clientOptions);
        $client->setDatabase('testDb');
        static::assertEquals('testDb', $client->getDatabase());
    }


    /**
     *
     */
    public function testSetGetEndPoint()
    {
        $client = new Client($this->connector, $this->clientOptions);
        $client->setEndpoint('http://db-link:8529');
        static::assertEquals('http://db-link:8529', $client->getEndpoint());
    }


    /**
     *
     */
    public function testSetGetPluginManager()
    {
        $client        = new Client($this->connector, $this->clientOptions);
        $pluginManager = new PluginManager($client);
        $client->setPluginManager($pluginManager);
        static::assertEquals($pluginManager, $client->getPluginManager());
    }


    /**
     *
     */
    public function testSetGetRequestClass()
    {
        $client       = new Client($this->connector, $this->clientOptions);
        $requestClass = new HttpRequest($client);
        $client->setRequestClass($requestClass);
        static::assertEquals($requestClass, $client->getRequestClass());
    }


    /**
     *
     */
    public function testSetGetResponseClass()
    {
        $client        = new Client($this->connector, $this->clientOptions);
        $responseClass = new HttpResponse();
        $client->setResponseClass($responseClass);
        static::assertEquals($responseClass, $client->getResponseClass());
    }
}
