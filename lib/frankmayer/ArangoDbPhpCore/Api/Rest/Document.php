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
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public function create(
        $collection = null,
        $body = null,
        $urlQuery = [],
        $options = []
    ) {

        /** @var Request $request */
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

        $responseObject = $request->send();

        return $responseObject;
    }


    /**
     * @param       $handle
     * @param       $body
     * @param array $urlQuery
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public function replace(
        $handle,
        $body,
        $urlQuery = [],
        $options = []
    ) {
        /** @var Request $request */
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

        $responseObject = $request->send();

        return $responseObject;
    }

    /**
     * @param       $handle
     * @param       $body
     * @param array $urlQuery
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public function update(
        $handle,
        $body,
        $urlQuery = [],
        $options = []
    ) {
        /** @var Request $request */
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

        $responseObject = $request->send();

        return $responseObject;
    }


    /**
     * @param       $collection
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public function getAllUri(
        $collection,
        $options = []
    ) {
        /** @var Request $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->path    = $request->getDatabasePath() . static::API_DOCUMENT;
        $request->path .= '?collection=' . $collection;
        $request->method = static::METHOD_GET;

        $responseObject = $request->send();

        return $responseObject;
    }


    /**
     * @param string $handle The document handle of the document we want to get. Example: MyCollection/22334
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public function get(
        $handle,
        $options = []
    ) {
        /** @var Request $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->path    = $request->getDatabasePath() . static::API_DOCUMENT . '/' . $handle;
        $request->method  = static::METHOD_GET;

        $responseObject = $request->send();

        return $responseObject;
    }


    /**
     * @param       $handle
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public function delete(
        $handle,
        $options = []
    ) {
        /** @var Request $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->path    = $request->getDatabasePath() . static::API_DOCUMENT . '/' . $handle;
        $request->method  = static::METHOD_DELETE;

        $responseObject = $request->send();

        return $responseObject;
    }
}