<?php
/**
 *
 * File: PromiseTest.php
 *
 * @package
 * @author Frank Mayer
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Integration;

require_once __DIR__ . '/TestCase.php';

use frankmayer\ArangoDbPhpCore\Client;


class SyncTest extends TestCase
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
        $this->connector = new Connector();

        $this->setupProperties();

    }


    /**
     * Test if we can get the server version
     */
    public function testSync()
    {
        $collectionParameters = [];

        $this->client->bind(
            'Request',
            function () {
                return $this->client->getRequest();
            }
        );
        $query = 'RETURN SLEEP(1)';

        $statement = ['query' => $query];

        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $request = $this->client->make('Request');

        $request->body = $statement;
        //        $request->connectorOptions = ['future' => true];

        $request->body = self::array_merge_recursive_distinct($request->body, $collectionParameters);
        $request->body = json_encode($request->body);

        $request->path   = $this->client->fullDatabasePath . self::URL_CURSOR;
        $request->method = self::METHOD_POST;

        $responseObject = $request->send();

        $body = $responseObject->body;

        static::assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        static::assertEquals(201, $decodedJsonBody['code']);
    }
}
