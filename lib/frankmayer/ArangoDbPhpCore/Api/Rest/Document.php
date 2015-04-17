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
use frankmayer\ArangoDbPhpCore\Protocols\Http\Request;


/**
 * A document class for testing and demonstration purposes
 *
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
     * @param       $client
     * @param       $collection
     * @param       $body
     * @param array $urlQuery
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public static function create($client, $collection = null, $body = null, $urlQuery = [], $options = [])
    {
        /** @var Request $request */
        $request          = new $client->requestClass();
        $request->client  = $client;
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
     * @param       $client
     * @param       $handle
     * @param       $body
     * @param array $urlQuery
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public static function replace($client, $handle, $body, $urlQuery = [], $options = [])
    {
        /** @var Request $request */
        $request          = new $client->requestClass();
        $request->client  = $client;
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
     * @param       $client
     * @param       $handle
     * @param       $body
     * @param array $urlQuery
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public static function update($client, $handle, $body, $urlQuery = [], $options = [])
    {
        /** @var Request $request */
        $request          = new $client->requestClass();
        $request->client  = $client;
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
     * @param       $client
     * @param       $collection
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public static function getAllUri($client, $collection, $options = [])
    {
        /** @var Request $request */
        $request          = new $client->requestClass();
        $request->client  = $client;
        $request->options = $options;
        $request->path    = $request->getDatabasePath() . static::API_DOCUMENT;
        $request->path .= '?collection=' . $collection;
        $request->method = static::METHOD_GET;

        $responseObject = $request->send();

        return $responseObject;
    }


    /**
     * @param        $client
     * @param string $handle The document handle of the document we want to get. Example: MyCollection/22334
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public static function get($client, $handle, $options = [])
    {
        /** @var Request $request */
        $request          = new $client->requestClass();
        $request->client  = $client;
        $request->options = $options;
        $request->path    = $request->getDatabasePath() . static::API_DOCUMENT . '/' . $handle;
        $request->method  = static::METHOD_GET;

        $responseObject = $request->send();

        return $responseObject;
    }


    /**
     * @param       $client
     * @param       $handle
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public static function delete($client, $handle, $options = [])
    {
        /** @var Request $request */
        $request          = new $client->requestClass();
        $request->client  = $client;
        $request->options = $options;
        $request->path    = $request->getDatabasePath() . static::API_DOCUMENT . '/' . $handle;
        $request->method  = static::METHOD_DELETE;

        $responseObject = $request->send();

        return $responseObject;
    }
}