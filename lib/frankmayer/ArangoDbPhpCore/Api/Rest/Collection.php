<?php

/**
 * ArangoDB PHP Core Client: client
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Api\Rest;

use frankmayer\ArangoDbPhpCore\Api\RestApiInterface;
use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\Protocols\Http\Request;


/**
 * A collection class for testing and demonstration purposes
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class Collection extends
    Api implements
    RestApiInterface
{

    /**
     *
     */
    const API_COLLECTION = '/_api/collection';


    /**
     * @param       $collectionName
     * @param array $collectionParameters
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public function create(
        $collectionName,
        $collectionParameters = [],
        $options = []
    ) {
        // Here's how a binding for the HttpRequest should take place in the IOC container.
        // The actual binding should only happen once in the client construction, though. This is only for testing...


        Client::bind(
            'httpRequest',
            function () {
                $request         = new $this->client->requestClass();
                $request->client = $this->client;

                return $request;
            }
        );

        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $request = Client::make('httpRequest');

        $request->options = $options;
        $request->body    = ['name' => $collectionName];

        $request->body = self::array_merge_recursive_distinct($request->body, $collectionParameters);
        $request->body = json_encode($request->body);

        $request->path   = $request->getDatabasePath() . self::API_COLLECTION;
        $request->method = self::METHOD_POST;

        $responseObject = $request->send();

        return $responseObject;
    }


    /**
     * @param       $collectionName
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public function delete(
        $collectionName,
        $options = []
    ) {
        /** @var Request $request */
        $request         = new $this->client->requestClass();
        $request->client = $this->client;

        $request->options = $options;
        $request->path    = $request->getDatabasePath() . self::API_COLLECTION . '/' . $collectionName;
        $request->method  = self::METHOD_DELETE;

        $responseObject = $request->send();

        return $responseObject;
    }


    /**
     * @param       $collectionName
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public function truncate(
        $collectionName,
        $options = []
    ) {
        /** @var Request $request */
        $request         = new $this->client->requestClass();
        $request->client = $this->client;

        $request->options = $options;

        $request->path   = $request->getDatabasePath() . self::API_COLLECTION . '/' . $collectionName . '/truncate';
        $request->method = self::METHOD_PUT;

        $responseObject = $request->send();

        return $responseObject;
    }


    /**
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public function getAll(
        $options = []
    ) {
        /** @var Request $request */
        $request         = new $this->client->requestClass();
        $request->client = $this->client;

        $request->options = $options;

        $request->path = $request->getDatabasePath() . self::API_COLLECTION;
        if (isset($request->options['excludeSystem']) && $request->options['excludeSystem'] === true) {
            $request->path .= '?excludeSystem=true';
        }
        $request->method = self::METHOD_GET;

        $responseObject = $request->send();

        return $responseObject;
    }
}
