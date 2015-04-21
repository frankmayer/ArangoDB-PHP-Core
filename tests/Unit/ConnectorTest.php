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
class ConnectorUnitTest extends ArangoDbPhpCoreUnitTestCase
{

    /**
     *
     */
    public function testIfCurlConnectorInstantiable()
    {
        $connector = new Connector();
        $this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector', $connector);
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