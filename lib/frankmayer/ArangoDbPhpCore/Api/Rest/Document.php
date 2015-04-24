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
use frankmayer\ArangoDbPhpCore\Protocols\Http\Request;


/**
 * A document class for testing and demonstration purposes
 *
 * @property  ClientInterface|Client $client
 * @package frankmayer\ArangoDbPhpCore
 */
class Document extends
    Api implements
    RestApiInterface
{
    public $urlQuery;

    /**
     *
     */
    const API_DOCUMENT = '/_api/document';


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

        $request->path = $request->getDatabasePath() . static::API_DOCUMENT;

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

        $request->path = $request->getDatabasePath() . static::API_DOCUMENT . '/' . $handle;

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

        $request->path = $request->getDatabasePath() . static::API_DOCUMENT . '/' . $handle;

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
        $request->path    = $request->getDatabasePath() . static::API_DOCUMENT;
        $request->path .= '?collection=' . $collection;
        $request->method = static::METHOD_GET;

        return $this->getReturnObject($request);
    }


    /**
     * @param string $handle The document handle of the document we want to get. Example: MyCollection/22334
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
        $request->path    = $request->getDatabasePath() . static::API_DOCUMENT . '/' . $handle;
        $request->method  = static::METHOD_GET;

        return $this->getReturnObject($request);
    }


    /**
     * @param string $handle The document handle of the document we want to get. Example: MyCollection/22334
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
        $request->path    = $request->getDatabasePath() . static::API_DOCUMENT . '/' . $handle;
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
        $request->path    = $request->getDatabasePath() . static::API_DOCUMENT . '/' . $handle;
        $request->method  = static::METHOD_DELETE;

        return $this->getReturnObject($request);
    }
}
