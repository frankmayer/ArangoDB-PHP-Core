<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Document Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2015, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Integration;

require_once __DIR__.'/ArangoDbPhpCoreIntegrationTestCase.php';

use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Api\Rest\Document;
use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\ClientException;
use HttpResponse;


/**
 * Class DocumentTest
 * @package frankmayer\ArangoDbPhpCore
 */
class DocumentIntegrationTest extends
    ArangoDbPhpCoreIntegrationTestCase
{
    /**
     * @var Client
     */
    public $client;


    /**
     * @throws ClientException
     */
    public function setUp()
    {
        $connector    = new Connector($this->client);
        $this->client = getClient($connector);

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions    = ['waitForSync' => true];
        $collectionParameters = [];
        $options              = $collectionOptions;
        $this->client->bind(
            'Request',
            function () {
                $request = $this->client->getRequest();

                return $request;
            }
        );


        $request          = $this->client->make('Request');
        $request->options = $options;
        $request->body    = ['name' => $collectionName];

        $request->body = self::array_merge_recursive_distinct($request->body, $collectionParameters);
        $request->body = json_encode($request->body);

        $request->path   = $this->client->fullDatabasePath . self::API_COLLECTION;
        $request->method = self::METHOD_POST;

        $responseObject = $request->send();

        $body = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);
    }


    /**
     *
     */
    public function testCreateInExistingCollection()
    {
        $collectionName       = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';
        $urlQuery             = [];
        $collectionOptions    = ['waitForSync' => true];
        $collectionParameters = [];
        $options              = $collectionOptions;
        $requestBody          = ['name' => 'frank', '_key' => '1'];

        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $request          = $this->client->make('Request');
        $request->options = $options;
        $request->body    = $requestBody;
        $request->body    = self::array_merge_recursive_distinct($request->body, $collectionParameters);
        $request->body    = json_encode($request->body);
        $request->path    = $this->client->fullDatabasePath . self::API_DOCUMENT;

        if (isset($collectionName)) {
            $urlQuery = array_merge(
                $urlQuery ? $urlQuery : [],
                ['collection' => $collectionName]
            );
        }

        $urlQuery = $request->buildUrlQuery($urlQuery);

        $request->path .= $urlQuery;
        $request->method = self::METHOD_POST;

        /** @var HttpResponse $responseObject */
        $responseObject = $request->send();

        $responseBody = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayNotHasKey('error', $decodedJsonBody);

        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateAndDeleteDocumentInNonExistingCollection()
    {
        $collectionName     = 'ArangoDB-PHP-Core-CollectionTestSuite-NonExistingCollection';
        $documentParameters = ['createCollection' => true];
        $requestBody        = ['name' => 'frank', '_key' => '1'];

        $document = new Document($this->client);

        /** @var HttpResponse $responseObject */
        $responseObject = $document->create($collectionName, $requestBody, $documentParameters);
        $responseBody   = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayHasKey('error', $decodedJsonBody);
        $this->assertEquals(true, $decodedJsonBody['error']);

        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);

        $collection = new Collection($this->client);

        $responseObject = $collection->drop($collectionName);
        $responseBody   = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($responseBody, true));

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals(200, $decodedJsonBody['code']);
    }

    /**
     * Test if we can get the server version
     */
    public function testCreateGetListGetDocumentAndDeleteDocumentInExistingCollection()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';
        $requestBody    = ['name' => 'frank', '_key' => '1'];
        $document       = new Document($this->client);

        /** @var HttpResponse $responseObject */
        $responseObject = $document->create($collectionName, $requestBody);
        $responseBody   = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayNotHasKey('error', $decodedJsonBody);

        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);

        $responseObject = $document->getAll($collectionName);
        $responseBody   = $responseObject->body;

        $this->assertArrayHasKey('documents', json_decode($responseBody, true));

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals(
            '/_api/document/ArangoDB-PHP-Core-CollectionTestSuite-Collection/1',
            $decodedJsonBody['documents'][0]
        );

        $responseObject = $document->delete($collectionName . '/1');
        $responseBody   = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayNotHasKey('error', $decodedJsonBody);

        // Try to delete a second time .. should throw an error
        $responseObject = $document->delete($collectionName . '/1');
        $responseBody   = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayHasKey('error', $decodedJsonBody);
        $this->assertEquals(true, $decodedJsonBody['error']);

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals(true, $decodedJsonBody['error']);

        $this->assertEquals(404, $decodedJsonBody['code']);

        $this->assertEquals(1202, $decodedJsonBody['errorNum']);
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateReplaceDocumentAndDeleteDocumentInExistingCollection()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';
        $requestBody    = ['name' => 'Frank', 'bike' => 'vfr', '_key' => '1'];

        $document = new Document($this->client);

        /** @var HttpResponse $responseObject */
        $responseObject = $document->create($collectionName, $requestBody);
        $responseBody   = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayNotHasKey('error', $decodedJsonBody);

        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);

        $requestBody = ['name' => 'Mike'];

        $document = new Document($this->client);

        $responseObject = $document->replace($collectionName . '/1', $requestBody);
        $responseBody   = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayNotHasKey('error', $decodedJsonBody);

        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);

        $document = new Document($this->client);

        $responseObject = $document->get($collectionName . '/1', $requestBody);
        $responseBody   = $responseObject->body;

        $this->assertArrayNotHasKey('bike', json_decode($responseBody, true));

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals('Mike', $decodedJsonBody['name']);

        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);

        $responseObject = $document->delete($collectionName . '/1');
        $responseBody   = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayNotHasKey('error', $decodedJsonBody);

        // Try to delete a second time .. should throw an error
        $responseObject = $document->delete($collectionName . '/1');
        $responseBody   = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayHasKey('error', $decodedJsonBody);
        $this->assertEquals(true, $decodedJsonBody['error']);

        $this->assertEquals(true, $decodedJsonBody['error']);

        $this->assertEquals(404, $decodedJsonBody['code']);

        $this->assertEquals(1202, $decodedJsonBody['errorNum']);
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateUpdateDocumentAndDeleteDocumentInExistingCollection()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';
        $requestBody    = ['name' => 'Frank', 'bike' => 'vfr', '_key' => '1'];

        $document = new Document($this->client);

        /** @var HttpResponse $responseObject */
        $responseObject = $document->create($collectionName, $requestBody);
        $responseBody   = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayNotHasKey('error', $decodedJsonBody);

        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);

        $requestBody = ['name' => 'Mike'];

        $document = new Document($this->client);

        $responseObject = $document->update($collectionName . '/1', $requestBody);
        $responseBody   = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayNotHasKey('error', $decodedJsonBody);

        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);

        $document = new Document($this->client);

        $responseObject = $document->get($collectionName . '/1', $requestBody);
        $responseBody   = $responseObject->body;

        $this->assertArrayHasKey('bike', json_decode($responseBody, true));

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals('Mike', $decodedJsonBody['name']);

        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);

        $responseObject = $document->delete($collectionName . '/1');
        $responseBody   = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayNotHasKey('error', $decodedJsonBody);

        // Try to delete a second time .. should throw an error
        $responseObject = $document->delete($collectionName . '/1');
        $responseBody   = $responseObject->body;

        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertArrayHasKey('error', $decodedJsonBody);

        $this->assertEquals(true, $decodedJsonBody['error']);

        $this->assertEquals(404, $decodedJsonBody['code']);

        $this->assertEquals(1202, $decodedJsonBody['errorNum']);
    }


    /**
     * @throws ClientException
     */
    public function tearDown()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions = ['waitForSync' => true];
        $options           = $collectionOptions;
        $this->client->bind(
            'Request',
            function () {
                $request = $this->client->getRequest();

                return $request;
            }
        );


        $request          = $this->client->make('Request');
        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . self::API_COLLECTION . '/' . $collectionName;
        $request->method  = self::METHOD_DELETE;

        /** @var HttpResponse $responseObject */
        $responseObject = $request->send();
        $body           = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));

        $decodedJsonBody = json_decode($body, true);

        $this->assertEquals(200, $decodedJsonBody['code']);

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-NonExistingCollection';
        $collection     = new Collection($this->client);

        /** @var $responseObject HttpResponse */
        $collection->drop($collectionName);
    }
}
