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
use frankmayer\ArangoDbPhpCore\ClientInterface;
use frankmayer\ArangoDbPhpCore\Protocols\Http\AbstractHttpRequest;


/**
 * A edge class for testing and demonstration purposes
 *
 * @property  ClientInterface|Client $client
 * @package frankmayer\ArangoDbPhpCore
 */
class Edge extends
    Document implements
    RestApiInterface
{
    public $urlQuery;

    /**
     *
     */
    const API_PATH = '/_api/edge';


    /**
     * @param       $collection
     * @param       $body
     * @param array $urlQuery
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function create(
        $collection = null,
        $body = null,
        $urlQuery = [],
        $options = []
    ) {

        /** @var AbstractHttpRequest $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->body    = $body;

        if (is_array($request->body)) {
            $request->body = json_encode($request->body);
        }

        $request->path = $this->client->fullDatabasePath . static::API_PATH;

        if (isset($collection)) {
            $urlQuery = array_merge(
                $urlQuery ? $urlQuery : [],
                ['collection' => $collection]
            );
        }

        $urlQuery = $request->buildUrlQuery($urlQuery);

        $request->path .= $urlQuery;

        $request->method = static::METHOD_POST;

        return $this->getReturnObject($request);
    }


    /**
     * @param       $handle
     * @param       $body
     * @param array $urlQuery
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function replace(
        $handle,
        $body,
        $urlQuery = [],
        $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->body    = $body;

        if (is_array($request->body)) {
            $request->body = json_encode($request->body);
        }

        $request->path = $this->client->fullDatabasePath . static::API_PATH . '/' . $handle;

        $urlQuery = $request->buildUrlQuery($urlQuery);

        $request->path .= $urlQuery;

        $request->method = static::METHOD_PUT;

        return $this->getReturnObject($request);
    }

    /**
     * @param       $handle
     * @param       $body
     * @param array $urlQuery
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function update(
        $handle,
        $body,
        $urlQuery = [],
        $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->body    = $body;

        if (is_array($request->body)) {
            $request->body = json_encode($request->body);
        }

        $request->path = $this->client->fullDatabasePath . static::API_PATH . '/' . $handle;

        $urlQuery = $request->buildUrlQuery($urlQuery);

        $request->path .= $urlQuery;

        $request->method = static::METHOD_PATCH;

        return $this->getReturnObject($request);
    }


    /**
     * @param       $collection
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function getAll(
        $collection,
        $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH;
        $request->path .= '?collection=' . $collection;
        $request->method = static::METHOD_GET;

        return $this->getReturnObject($request);
    }


    /**
     * @param string $handle The edge handle of the edge we want to get. Example: MyCollection/22334
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function get(
        $handle,
        $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH . '/' . $handle;
        $request->method  = static::METHOD_GET;

        return $this->getReturnObject($request);
    }


    /**
     * @param string $handle The edge handle of the edge we want to get. Example: MyCollection/22334
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function getHeader(
        $handle,
        $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH . '/' . $handle;
        $request->method  = static::METHOD_HEAD;

        return $this->getReturnObject($request);
    }

    /**
     * @param       $handle
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function delete(
        $handle,
        $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH . '/' . $handle;
        $request->method  = static::METHOD_DELETE;

        return $this->getReturnObject($request);
    }
}
