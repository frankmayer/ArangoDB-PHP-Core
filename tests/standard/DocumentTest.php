<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Document Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140 as ArangoDbApi;

use frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector;


class DocumentTest extends
    \PHPUnit_Framework_TestCase
{
    public $client;


    public function setUp()
    {
        $connector    = new CurlHttpConnector();
        $this->client = $this->client = getClient($connector);

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions = array("waitForSync" => true);

        $collection     = new ArangoDbApi\Collection($this->client);
        $responseObject = $collection->create($collectionName, $collectionOptions);

        $body = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateInExistingCollection()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $requestBody    = array('name' => 'frank', '_key' => '1');
        $document       = new ArangoDbApi\Document($this->client);
        $responseObject = $document->create($collectionName, $requestBody);

        $responseBody = $responseObject->body;

        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $decodedJsonBody = json_decode($responseBody, true);
        $this->assertEquals(false, $decodedJsonBody['error']);
        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateAndDeleteDocumentInNonExistingCollection()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-NonExistingCollection';


        $documentParameters = array('createCollection' => true);
        $requestBody        = array('name' => 'frank', '_key' => '1');
        $document           = new ArangoDbApi\Document($this->client);
        $responseObject     = $document->create($collectionName, $requestBody, $documentParameters);

        $responseBody = $responseObject->body;

        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals(false, $decodedJsonBody['error']);
        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);

        $collection = new ArangoDbApi\Collection($this->client);

        $responseObject = $collection->delete($collectionName);
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


        $requestBody    = array('name' => 'frank', '_key' => '1');
        $document       = new ArangoDbApi\Document($this->client);
        $responseObject = $document->create($collectionName, $requestBody);

        $responseBody = $responseObject->body;

        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $decodedJsonBody = json_decode($responseBody, true);
        $this->assertEquals(false, $decodedJsonBody['error']);
        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);

        $responseObject = $document->getAllUri($collectionName);

        $responseBody = $responseObject->body;
        $this->assertArrayHasKey('documents', json_decode($responseBody, true));
        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals(
             '/_api/document/ArangoDB-PHP-Core-CollectionTestSuite-Collection/1',
             $decodedJsonBody['documents'][0]
        );

        $responseObject = $document->delete($collectionName . '/1');

        $responseBody = $responseObject->body;
        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $decodedJsonBody = json_decode($responseBody, true);
        $this->assertEquals(false, $decodedJsonBody['error']);

        // Try to delete a second time .. should throw an error
        $responseObject = $document->delete($collectionName . '/1');

        $responseBody = $responseObject->body;
        $this->assertArrayHasKey('error', json_decode($responseBody, true));
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

        $requestBody    = array('name' => 'Frank', 'bike' => 'vfr', '_key' => '1');
        $document       = new ArangoDbApi\Document($this->client);
        $responseObject = $document->create($collectionName, $requestBody);

        $responseBody = $responseObject->body;

        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals(false, $decodedJsonBody['error']);
        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);
        //

        $requestBody    = array('name' => 'Mike');
        $document       = new ArangoDbApi\Document($this->client);
        $responseObject = $document->replace($collectionName . '/1', $requestBody);

        $responseBody = $responseObject->body;

        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals(false, $decodedJsonBody['error']);
        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);


        $document       = new ArangoDbApi\Document($this->client);
        $responseObject = $document->get($collectionName . '/1', $requestBody);

        $responseBody = $responseObject->body;

        $this->assertArrayNotHasKey('bike', json_decode($responseBody, true));
        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals('Mike', $decodedJsonBody['name']);
        $this->assertEquals($collectionName . '/1', $decodedJsonBody['_id']);

        $responseObject = $document->delete($collectionName . '/1');

        $responseBody = $responseObject->body;
        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals(false, $decodedJsonBody['error']);

        // Try to delete a second time .. should throw an error
        $responseObject = $document->delete($collectionName . '/1');

        $responseBody = $responseObject->body;
        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $decodedJsonBody = json_decode($responseBody, true);

        $this->assertEquals(true, $decodedJsonBody['error']);
        $this->assertEquals(404, $decodedJsonBody['code']);
        $this->assertEquals(1202, $decodedJsonBody['errorNum']);
    }


    public function tearDown()
    {

        $collectionNames[0] = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';
        $collectionNames[1] = 'ArangoDB-PHP-Core-CollectionTestSuite-NonExistingCollection';

        foreach ($collectionNames as $collectionName) {
            $collection = new ArangoDbApi\Collection($this->client);

            $responseObject = $collection->delete($collectionName);
            $responseObject->body;
        }
    }
}
