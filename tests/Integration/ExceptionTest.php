<?php
/**
 *
 * File: PromiseTest.php
 *
 * @package
 * @author Frank Mayer
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Integration;


use frankmayer\ArangoDbPhpCore\Client;

require_once __DIR__.'/ArangoDbPhpCoreIntegrationTestCase.php';

class ExceptionIntegrationTest extends ArangoDbPhpCoreIntegrationTestCase
{

    /**
     * base URL part for cursor related operations
     */
    const URL_CURSOR = '/_api/cursor';

    const API_COLLECTION = '/_api/collection';

    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_PATCH   = 'PATCH';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_HEAD    = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';

    /**
     * @var Client
     */
    public $client;


    public function setUp()
    {
        $connector    = new Connector();
        $this->client = getClient($connector);
    }

    public function testTimeoutException()
    {
        //        $query = 'RETURN SLEEP(13)';
        //
        //        $statement = new Statement($this->connection, ["query" => $query]);
        //
        //        try {
        //            $statement->execute();
        //        } catch (ClientException $exception) {
        //            $this->assertEquals($exception->getCode(), 408);
        //            throw $exception;
        //        }
    }
}
