<?php
/**
 *
 * File: CoreTest.php
 *
 * @package
 * @author Frank Mayer
 */
namespace frankmayer\ArangoDbPhpCore;

require_once('ArangoDbPhpCoreUnitTestCase.php');


/**
 * Class CoreTest
 * @package frankmayer\ArangoDbPhpCore
 */
class CoreTest extends ArangoDbPhpCoreUnitTestCase
{
    /**
     *
     */
    public function testIfCurlConnectorInstantiable()
    {
        $connector = new \frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector();
        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector', $connector);
    }

    /**
     *
     */
    public function testIfCurlClientInstantiable()
    {
        $connector    = new \frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector();
        $this->client = $this->client = getClient($connector);
        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Client', $this->client);
    }

    /**
     *
     */
    public function testIfFSockConnectorInstantiable()
    {
        // The FSock connector has not been implemented yet
    }
}