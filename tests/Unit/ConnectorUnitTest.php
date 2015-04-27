<?php
/**
 *
 * File: CoreTest.php
 *
 * @package
 * @author Frank Mayer
 */
namespace frankmayer\ArangoDbPhpCore;

require_once 'ArangoDbPhpCoreUnitTestCase.php';


/**
 * Class CoreTest
 * @package frankmayer\ArangoDbPhpCore
 */
class ConnectorUnitTest extends ArangoDbPhpCoreUnitTestCase
{
    private $connector;

    public function setup()
    {
        $this->connector = $this->getMockBuilder('TestConnector')
                                ->getMock();
    }


    /**
     *
     */
    public function testIfCurlConnectorInstantiable()
    {

        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Connectors\AbstractHttpConnector', $this->connector);
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
