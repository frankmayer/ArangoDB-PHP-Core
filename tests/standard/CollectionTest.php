<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Collection Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;


use frankmayer\ArangoDbPhpCore\Connectors\CurlHttp\Connector;

class CollectionTest extends
    \PHPUnit_Framework_TestCase
{
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
        $this->client = $this->client = getClient($connector);

        //        Client::bind(
        //            'ArangoCollection',
        //            function () {
        //                $instance         = new ArangoDbApi\Collection();
        //                $instance->client = $this->client;
        //
        //                return $instance;
        //            }
        //        );
    }


    /**
     * Test if we can get the server version
     */
    public function oldtestCreateCollection()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions = ["waitForSync" => true];

        $collection         = new ArangoDbApi\Collection();
        $collection->client = $this->client;
        $responseObject     = $collection->create($collectionName, $collectionOptions);
        $body               = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);
    }


    /**
     * Test if we can get the server version
     */
    public function testCreateCollection()
    {
        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions    = ["waitForSync" => true];
        $collectionParameters = [];
        $options              = $collectionOptions;
        Client::bind(
            'Request',
            function () {
                $request         = new $this->client->requestClass();
                $request->client = $this->client;

                return $request;
            }
        );

        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $request          = Client::make('Request');
        $request->options = $options;
        $request->body    = ['name' => $collectionName];

        $request->body = self::array_merge_recursive_distinct($request->body, $collectionParameters);
        $request->body = json_encode($request->body);

        $request->path   = $request->getDatabasePath() . self::API_COLLECTION;
        $request->method = self::METHOD_POST;

        $responseObject = $request->request();

        //        return $responseObject;
        $body = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
        $this->assertEquals($collectionName, $decodedJsonBody['name']);
    }


    /**
     * Test if we can get the server version
     */
    public function testDeleteCollection()
    {

        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

        $collectionOptions    = ["waitForSync" => true];
        $collectionParameters = [];
        $options              = $collectionOptions;
        Client::bind(
            'Request',
            function () {
                $request         = new $this->client->requestClass();
                $request->client = $this->client;

                return $request;
            }
        );

        $request          = Client::make('Request');

        $request->options = $options;
        $request->path    = $request->getDatabasePath() . self::API_COLLECTION . '/' . $collectionName;
        $request->method  = self::METHOD_DELETE;

        $responseObject = $request->request();
        $body               = $responseObject->body;

        $this->assertArrayHasKey('code', json_decode($body, true));
        $decodedJsonBody = json_decode($body, true);
        $this->assertEquals(200, $decodedJsonBody['code']);
    }



    //
    //    /**
    //     * Test if we can get the server version
    //     */
    //    public function testCreateCollectionViaIocContainer()
    //    {
    //        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-CollectionViaIocContainer';
    //
    //        $collectionOptions = ["waitForSync" => true];
    //
    //
    //        // Here's how a binding for the HttpRequest should take place in the IOC container.
    //        // The actual binding should only happen once in the client construction, though. This is only for testing...
    //
    //
    //        // And here's how one gets an HttpRequest object through the IOC.
    //        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
    //        $collection = Client::make('ArangoCollection');
    //
    //        /** @var $collection Collection */
    //        $responseObject = $collection->create($collectionName, $collectionOptions);
    //        $body           = $responseObject->body;
    //
    //        $this->assertArrayHasKey('code', json_decode($body, true));
    //        $decodedJsonBody = json_decode($body, true);
    //        $this->assertEquals(200, $decodedJsonBody['code']);
    //        $this->assertEquals($collectionName, $decodedJsonBody['name']);
    //    }
    //
    //
    //    /**
    //     * Test if we can get the server version
    //     */
    //    public function testTruncateCollection()
    //    {
    //        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';
    //
    //
    //        $collection         = new ArangoDbApi\Collection();
    //        $collection->client = $this->client;
    //        $responseObject     = $collection->truncate($collectionName);
    //        $body               = $responseObject->body;
    //
    //        $this->assertArrayHasKey('code', json_decode($body, true));
    //        $decodedJsonBody = json_decode($body, true);
    //        $this->assertEquals(200, $decodedJsonBody['code']);
    //        $this->assertEquals($collectionName, $decodedJsonBody['name']);
    //    }
    //
    //
    //    /**
    //     * Test if we can get the server version
    //     */
    //    public function testDeleteCollection()
    //    {
    //
    //        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';
    //
    //
    //        $collection         = new ArangoDbApi\Collection();
    //        $collection->client = $this->client;
    //        $responseObject     = $collection->delete($collectionName);
    //        $body               = $responseObject->body;
    //
    //        $this->assertArrayHasKey('code', json_decode($body, true));
    //        $decodedJsonBody = json_decode($body, true);
    //        $this->assertEquals(200, $decodedJsonBody['code']);
    //    }
    //
    //
    //    /**
    //     * Test if we can get all collections
    //     */
    //    public function testGetCollections()
    //    {
    //        $collection         = new ArangoDbApi\Collection();
    //        $collection->client = $this->client;
    //        $responseObject     = $collection->getAll();
    //        $response           = json_decode($responseObject->body);
    //
    //        $this->assertObjectHasAttribute('_graphs', $response->names);
    //    }
    //
    //    /**
    //     * Test if we can get all collections
    //     */
    //    public function testGetCollectionsExcludeSystem()
    //    {
    //        $collection         = new ArangoDbApi\Collection();
    //        $collection->client = $this->client;
    //        $responseObject     = $collection->getAll(['excludeSystem' => true]);
    //        $response           = json_decode($responseObject->body);
    //
    //        $this->assertObjectNotHasAttribute('_graphs', $response->names);
    //    }
    //
    //    public function tearDown()
    //    {
    //        $collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-CollectionViaIocContainer';
    //
    //        $collection = Client::make('ArangoCollection');
    //        $collection->delete($collectionName);
    //    }
    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * array_merge_recursive_distinct does not change the data-types of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     *
     * Note: thanks Gabriel and Daniel for your initial idea/work. I only made it static ;)
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     * @author Frank Mayer
     */
    public static function array_merge_recursive_distinct(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset ($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = self::array_merge_recursive_distinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
    }
}
