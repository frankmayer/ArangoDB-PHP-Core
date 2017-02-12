<?php
/**
 *
 * File: CoreTest.php
 *
 * @package
 * @author Frank Mayer
 */
namespace frankmayer\ArangoDbPhpCore\Tests\Unit;

use frankmayer\ArangoDbPhpCore\Connectors\AbstractHttpConnector;

require_once __DIR__ . '/TestCase.php';


/**
 * Class CoreTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class ConnectorTest extends TestCase
{
    protected $connector;

    public function setup()
    {

        $this->connector = $this->getMockBuilder(\TestConnector::class)
            ->getMock();

        $this->setupProperties();
    }


    /**
     *
     */
    public function testIfCurlConnectorInstantiable()
    {

        $this->assertInstanceOf(AbstractHttpConnector::class, $this->connector);
    }


    /**
     *
     */
    public function testSetGetVerboseLogging()
    {
        $connector = new \TestConnector();
        $connector->setVerboseLogging(true);
        $res = $connector->getVerboseLogging();

        $this->assertTrue($res);
    }


    /**
     *
     */
    public function testCurlRequest()
    {
        // todo: check if it's wise to abstract curl functions
        // Testing is not possible without abstracting the curl functions in another class.
        // This would mean introducing and instantiating another class.
        // I don't know if this makes sense... Maybe later...
    }


    /**
     *
     */
    public function testFSockRequest()
    {
        // FSock Requests are not implemented in the core, yet (and maybe never)
        // The functionality to connect to sockets instead to tcp endpoints can be provided
        // by the ArangoDB-PHP-Core-Guzzle Provider.
        // todo: check if this actually works
    }
}
