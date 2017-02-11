<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Database Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Integration;

require_once __DIR__ . '/ArangoDbPhpCoreIntegrationTestCase.php';

use frankmayer\ArangoDbPhpCore\Api\Rest\Database;
use frankmayer\ArangoDbPhpCore\Client;
use HttpResponse;

//todo: fix tests

/**
 * Class DatabaseTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class DatabaseIntegrationTest extends ArangoDbPhpCoreIntegrationTestCase
{
    /**
     * @var Client
     */
    public $client;


    /**
     *
     */
    public function setUp()
    {
        $connector    = new Connector();
        $this->client = $this->client = getClient($connector);
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateDatabaseWithoutApiClasses()
    {
        $databaseName = 'ArangoDB-PHP-Core-DatabaseTestSuite-Database';

        $databaseOptions    = ['waitForSync' => true];
        $databaseParameters = [];
        $options            = $databaseOptions;
        $this->client->bind(
            'Request',
            function () {
                $request = $this->client->getRequest();

                return $request;
            }
        );

        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $request          = $this->client->make('Request');
        $request->options = $options;
        $request->body    = ['name' => $databaseName];

        $request->body = self::array_merge_recursive_distinct($request->body, $databaseParameters);
        $request->body = json_encode($request->body);

        $request->path   = $this->client->fullDatabasePath . self::API_DATABASE;
        $request->method = self::METHOD_POST;

        $responseObject = $request->send();

        $body = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($databaseName, $decodedJsonBody['name']);
    }


    /**
     * Test if we can get the server version
     */
    public function testDeleteDatabaseWithoutApiClasses()
    {
        $databaseName = 'ArangoDB-PHP-Core-DatabaseTestSuite-Database';

        $databaseOptions = ['waitForSync' => true];
        $options         = $databaseOptions;
        $this->client->bind(
            'Request',
            function () {
                $request = $this->client->getRequest();

                return $request;
            }
        );


        $request = $this->client->make('Request');

        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . self::API_DATABASE . '/' . $databaseName;
        $request->method  = self::METHOD_DELETE;

        $responseObject = $request->send();
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateDatabaseViaIocContainer()
    {
        $databaseName = 'ArangoDB-PHP-Core-DatabaseTestSuite-Database';

        $databaseOptions = ['waitForSync' => true];


        $database = new Database($this->client);

        /** @var $responseObject HttpResponse */
        $responseObject = $database->create($databaseName, $databaseOptions);

        $body = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($databaseName, $decodedJsonBody['name']);
    }


    /**
     * Test if we can get the server version
     */
    public function testTruncateDatabase()
    {
        $databaseName = 'ArangoDB-PHP-Core-DatabaseTestSuite-Database';

        $database = new Database($this->client);

        /** @var $responseObject HttpResponse */
        $responseObject = $database->truncate($databaseName);

        $body = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($databaseName, $decodedJsonBody['name']);
    }


    /**
     * Test if we can get the server version
     */
    public function testDeleteDatabase()
    {

        $databaseName = 'ArangoDB-PHP-Core-DatabaseTestSuite-Database';

        $database = new Database($this->client);

        /** @var $responseObject HttpResponse */
        $responseObject = $database->drop($databaseName);

        $body = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
    }


    /**
     * Test if we can get all databases
     */
    public function testGetDatabases()
    {
        $database = new Database($this->client);

        /** @var $responseObject HttpResponse */
        $responseObject = $database->getAll();

        $response = json_decode($responseObject->body);

        $this->assertObjectHasAttribute('_graphs', $response->names);
    }


    /**
     * Test if we can get all databases
     */
    public function testGetDatabasesExcludeSystem()
    {
        $database = new Database($this->client);

        /** @var $responseObject HttpResponse */
        $responseObject = $database->getAll(['excludeSystem' => true]);

        $response = json_decode($responseObject->body);

        $this->assertObjectNotHasAttribute('_graphs', $response->names);
    }


    /**
     *
     */
    public function tearDown()
    {
        $databaseName = 'ArangoDB-PHP-Core-DatabaseTestSuite-DatabaseViaIocContainer';
        $database     = new Database($this->client);

        /** @var $responseObject HttpResponse */
        $database->drop($databaseName);
    }
}
