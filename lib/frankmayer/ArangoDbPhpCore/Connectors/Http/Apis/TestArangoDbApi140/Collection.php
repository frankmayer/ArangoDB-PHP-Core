<?php

/**
 * ArangoDB PHP Core Client: client
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140;

use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest;


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

    //    public function __construct($client)
    //    {
    //                $this->client       = $client;
    //                $this->connector    = $this->client->connector;
    //                $this->requestClass = $this->client->requestClass;
    //    }

    /**
     * @param       $collectionName
     * @param array $collectionParameters
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function create(
        $collectionName,
        $collectionParameters = array(),
        $options = array()
    ) {
        // Here's how a binding for the HttpRequest should take place in the IOC container.
        // The actual binding should only happen once in the client construction, though. This is only for testing...

        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            // This is the way to bind an HttpRequest in PHP 5.4+

            Client::bind(
                  'httpRequest',
                      function () {
                          return new HttpRequest($this->client);
                      }
            );
        } else {
            // This is the way to bind an HttpRequest in PHP 5.3.x

            $me = $this;
            Client::bind(
                  'httpRequest',
                      function () use ($me) {
                          return new HttpRequest($me->client);
                      }
            );
        }
        // And here's how one gets an HttpRequest object through the IOC.
        // Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
        $this->request = Client::make('httpRequest');

        //        $this->request    = $requestClass::construct($this->client);
        $request          = $this->request;
        $request->options = $options;
        $request->body    = array('name' => $collectionName);

        $request->body = self::array_merge_recursive_distinct($request->body, $collectionParameters);
        $request->body = json_encode($request->body);

        $request->path   = $this->client->getDatabasePath() . self::API_COLLECTION;
        $request->method = self::METHOD_POST;

        $responseObject = $request->request();

        return $responseObject;
    }


    /**
     * @param       $collectionName
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function delete(
        $collectionName,
        $options = array()
    ) {
        $this->request = new $this->client->requestClass($this->client);
        //        $this->request    = new $this->client->make('requestClass');
        $request          = $this->request;
        $request->options = $options;
        $request->path    = $this->client->getDatabasePath() . self::API_COLLECTION . '/' . $collectionName;
        $request->method  = self::METHOD_DELETE;

        $responseObject = $request->request();

        return $responseObject;
    }


    /**
     * @param       $collectionName
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function truncate(
        $collectionName,
        $options = array()
    ) {
        $this->request    = new $this->client->requestClass($this->client);
        $request          = $this->request;
        $request->options = $options;

        $request->path   = $this->client->getDatabasePath(
            ) . self::API_COLLECTION . '/' . $collectionName . '/truncate';
        $request->method = self::METHOD_PUT;

        $responseObject = $request->request();

        return $responseObject;
    }


    /**
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function getAll(
        $options = array()
    ) {
        $this->request    = new $this->client->requestClass($this->client);
        $request          = $this->request;
        $request->options = $options;

        $request->path = $this->client->getDatabasePath() . self::API_COLLECTION;
        if (isset($request->options['excludeSystem']) && $request->options['excludeSystem'] === true) {
            $request->path .= '?excludeSystem=true';
        }
        $request->method = self::METHOD_GET;

        $responseObject = $request->request();

        return $responseObject;
    }
}
