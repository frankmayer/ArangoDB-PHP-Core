<?php

/**
 * ArangoDB PHP Core Client Integration Test-Suite: Database Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Integration;

require_once __DIR__ . '/TestCase.php';

use frankmayer\ArangoDbPhpCore\Api\Rest\Database;
use frankmayer\ArangoDbPhpCore\Client;
use HttpResponse;

//todo: fix tests

/**
 * Class DatabaseTest
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class DatabaseTest extends TestCase
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
        $this->connector = new Connector();

        $this->setupProperties();

    }


    /**
     * Test if we can get the server version
     */
    public function testCreateDatabaseWithoutApiClasses()
    {
        $databaseName = $this->TESTNAMES_PREFIX . 'DatabaseTestSuite-Database';

        $databaseOptions    = ['waitForSync' => true];
        $databaseParameters = [];
        $options            = $databaseOptions;
        $this->client->bind(
            'Request',
            function () {
                return $this->client->getRequest();
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
        $responseObject = $this->resolveResponse($responseObject);

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
        $databaseName = $this->TESTNAMES_PREFIX . 'DatabaseTestSuite-Database';

        $databaseOptions = ['waitForSync' => true];
        $options         = $databaseOptions;
        $this->client->bind(
            'Request',
            function () {
                return $this->client->getRequest();
            }
        );


        $request = $this->client->make('Request');

        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . self::API_DATABASE . '/' . $databaseName;
        $request->method  = self::METHOD_DELETE;

        $responseObject = $request->send();
        $responseObject = $this->resolveResponse($responseObject);
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
        $databaseName = $this->TESTNAMES_PREFIX . 'DatabaseTestSuite-Database';

        $databaseOptions = ['waitForSync' => true];


        $database = new Database($this->client);

        /** @var $responseObject HttpResponse */
        $responseObject = $database->create($databaseName, $databaseOptions);
        $responseObject = $this->resolveResponse($responseObject);

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
        $databaseName = $this->TESTNAMES_PREFIX . 'DatabaseTestSuite-Database';

        $database = new Database($this->client);

        /** @var $responseObject HttpResponse */
        $responseObject = $database->truncate($databaseName);
        $responseObject = $this->resolveResponse($responseObject);

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

        $databaseName = $this->TESTNAMES_PREFIX . 'DatabaseTestSuite-Database';

        $database = new Database($this->client);

        /** @var $responseObject HttpResponse */
        $responseObject = $database->drop($databaseName);
        $responseObject = $this->resolveResponse($responseObject);

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
        $responseObject = $this->resolveResponse($responseObject);

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
        $responseObject = $this->resolveResponse($responseObject);

        $response = json_decode($responseObject->body);

        $this->assertObjectNotHasAttribute('_graphs', $response->names);
    }


    /**
     *
     */
    public function tearDown()
    {
        $databaseName = $this->TESTNAMES_PREFIX . 'DatabaseTestSuite-DatabaseViaIocContainer';
        $database     = new Database($this->client);

        /** @var $responseObject HttpResponse */
        $responseObject = $database->drop($databaseName);
        $responseObject = $this->resolveResponse($responseObject);
    }
}
