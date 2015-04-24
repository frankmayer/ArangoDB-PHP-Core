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
use frankmayer\ArangoDbPhpCore\Protocols\Http\AbstractHttpRequest;


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

    public $client;


    /**
     * @param       $collectionName
     * @param array $collectionParameters
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     * @throws \frankmayer\ArangoDbPhpCore\ClientException
     */
    public function create(
        $collectionName,
        $collectionParameters = [],
        $options = []
    ) {
        // Here's how a binding for the HttpRequest should take place in the IOC container.
        // The actual binding should only happen once in the client construction, though. This is only for testing...
        //
        //        Client::bind(
        //            'httpRequest',
        //            function () {
        //                $request         = new $this->client->requestClass();
        //                $request->client = $this->client;
        //
        //                return $request;
        //            }
        //        );
        //
        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        //        $request = Client::make('httpRequest');
        //
        // We're not doing the above though, in Core, in order to keep a better performance

        $request         = new $this->client->requestClass();
        $request->client = $this->client;

        $request->options = $options;
        $request->body    = ['name' => $collectionName];

        $request->body = static::array_merge_recursive_distinct($request->body, $collectionParameters);
        $request->body = json_encode($request->body);

        $request->path   = $this->client->fullDatabasePath . static::API_COLLECTION;
        $request->method = static::METHOD_POST;

        return $this->getReturnObject($request);
    }


    /**
     * @param       $collectionName
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function drop(
        $collectionName,
        $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request         = new $this->client->requestClass();
        $request->client = $this->client;

        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_COLLECTION . '/' . $collectionName;
        $request->method  = static::METHOD_DELETE;

        return $this->getReturnObject($request);
    }


    /**
     * @param       $collectionName
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function truncate(
        $collectionName,
        $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request         = new $this->client->requestClass();
        $request->client = $this->client;

        $request->options = $options;

        $request->path   = $this->client->fullDatabasePath . static::API_COLLECTION . '/' . $collectionName . '/truncate';
        $request->method = static::METHOD_PUT;

        return $this->getReturnObject($request);
    }


    /**
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function getAll(
        $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request         = new $this->client->requestClass();
        $request->client = $this->client;

        $request->options = $options;

        $request->path = $this->client->fullDatabasePath . static::API_COLLECTION;
        if (isset($request->options['excludeSystem']) && $request->options['excludeSystem'] === true) {
            $request->path .= '?excludeSystem=true';
        }

        $request->method = static::METHOD_GET;

        return $this->getReturnObject($request);
    }
}
