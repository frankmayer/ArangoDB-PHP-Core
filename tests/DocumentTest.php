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
    protected $client;


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
        $this->assertEquals(200, json_decode($body, true)['code']);
        $this->assertEquals($collectionName, json_decode($body, true)['name']);
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
        $this->assertEquals(false, json_decode($responseBody, true)['error']);
        $this->assertEquals($collectionName . '/1', json_decode($responseBody, true)['_id']);
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
        $this->assertEquals(false, json_decode($responseBody, true)['error']);
        $this->assertEquals($collectionName . '/1', json_decode($responseBody, true)['_id']);

        $collection = new ArangoDbApi\Collection($this->client);

        $responseObject = $collection->delete($collectionName);
        $responseBody   = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($responseBody, true));
        $this->assertEquals(200, json_decode($responseBody, true)['code']);
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
        $this->assertEquals(false, json_decode($responseBody, true)['error']);
        $this->assertEquals($collectionName . '/1', json_decode($responseBody, true)['_id']);

        $responseObject = $document->getAllUri($collectionName);

        $responseBody = $responseObject->body;
        //        var_dump($responseBody);
        $this->assertArrayHasKey('documents', json_decode($responseBody, true));
        $this->assertEquals(
             '/_api/document/ArangoDB-PHP-Core-CollectionTestSuite-Collection/1',
             json_decode($responseBody, true)['documents'][0]
        );

        $responseObject = $document->delete($collectionName . '/1');

        $responseBody = $responseObject->body;
        //        var_dump($responseBody);
        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $this->assertEquals(false, json_decode($responseBody, true)['error']);

        // Try to delete a second time .. should throw an error
        $responseObject = $document->delete($collectionName . '/1');

        $responseBody = $responseObject->body;
        //        var_dump($responseBody);
        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $this->assertEquals(true, json_decode($responseBody, true)['error']);
        $this->assertEquals(404, json_decode($responseBody, true)['code']);
        $this->assertEquals(1202, json_decode($responseBody, true)['errorNum']);
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
        $this->assertEquals(false, json_decode($responseBody, true)['error']);
        $this->assertEquals($collectionName . '/1', json_decode($responseBody, true)['_id']);
        //

        $requestBody    = array('name' => 'Mike');
        $document       = new ArangoDbApi\Document($this->client);
        $responseObject = $document->replace($collectionName . '/1', $requestBody);

        $responseBody = $responseObject->body;
        //                var_dump($responseBody);

        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $this->assertEquals(false, json_decode($responseBody, true)['error']);
        $this->assertEquals($collectionName . '/1', json_decode($responseBody, true)['_id']);


        $document       = new ArangoDbApi\Document($this->client);
        $responseObject = $document->get($collectionName . '/1', $requestBody);

        $responseBody = $responseObject->body;
        //                var_dump($responseBody);

        $this->assertArrayNotHasKey('bike', json_decode($responseBody, true));
        $this->assertEquals('Mike', json_decode($responseBody, true)['name']);
        $this->assertEquals($collectionName . '/1', json_decode($responseBody, true)['_id']);


        //
        $responseObject = $document->delete($collectionName . '/1');

        $responseBody = $responseObject->body;
        //        var_dump($responseBody);
        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $this->assertEquals(false, json_decode($responseBody, true)['error']);

        // Try to delete a second time .. should throw an error
        $responseObject = $document->delete($collectionName . '/1');

        $responseBody = $responseObject->body;
        //        var_dump($responseBody);
        $this->assertArrayHasKey('error', json_decode($responseBody, true));
        $this->assertEquals(true, json_decode($responseBody, true)['error']);
        $this->assertEquals(404, json_decode($responseBody, true)['code']);
        $this->assertEquals(1202, json_decode($responseBody, true)['errorNum']);
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
