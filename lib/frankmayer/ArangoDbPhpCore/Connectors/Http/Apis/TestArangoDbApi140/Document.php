<?php

/**
 * ArangoDB PHP Core Client: client
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140;


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
     * @param       $collection
     * @param       $body
     * @param array $urlQuery
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function create(
        $collection = null,
        $body = null,
        $urlQuery = array(),
        $options = array()
    ) {
        $this->request         = new $this->client->requestClass();
        $this->request->client = $this->client;

        $request = $this->request;

        //        $urlQuery = $urlQuery;
        $request->options = $options;
        $request->body    = $body;

        if (is_array($request->body)) {
            $request->body = json_encode($request->body);
        }

        $request->path = $this->request->getDatabasePath() . self::API_DOCUMENT;

        if (isset($collection)) {
            $urlQuery = array_merge(
                $urlQuery ? $urlQuery : array(),
                array('collection' => $collection)
            );
        }

        $urlQuery = $this->buildUrlQuery($urlQuery);

        $request->path .= $urlQuery;

        $request->method = self::METHOD_POST;

        $responseObject = $request->request();

        return $responseObject;
    }


    /**
     * @param       $handle
     * @param       $body
     * @param array $urlQuery
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function replace(
        $handle,
        $body,
        $urlQuery = array(),
        $options = array()
    ) {
        $this->request         = new $this->client->requestClass();
        $this->request->client = $this->client;

        $request = $this->request;

        //        $urlQuery = $urlQuery;
        $request->options = $options;
        $request->body    = $body;

        if (is_array($request->body)) {
            $request->body = json_encode($request->body);
        }

        $request->path = $this->request->getDatabasePath() . self::API_DOCUMENT . '/' . $handle;

        $urlQuery = $this->buildUrlQuery($urlQuery);

        $request->path .= $urlQuery;

        $request->method = self::METHOD_PUT;

        $responseObject = $request->request();

        return $responseObject;
    }

    /**
     * @param       $handle
     * @param       $body
     * @param array $urlQuery
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function update(
        $handle,
        $body,
        $urlQuery = array(),
        $options = array()
    ) {
        $this->request         = new $this->client->requestClass();
        $this->request->client = $this->client;

        $request = $this->request;

        //        $urlQuery = $urlQuery;
        $request->options = $options;
        $request->body    = $body;

        if (is_array($request->body)) {
            $request->body = json_encode($request->body);
        }

        $request->path = $this->request->getDatabasePath() . self::API_DOCUMENT . '/' . $handle;

        $urlQuery = $this->buildUrlQuery($urlQuery);

        $request->path .= $urlQuery;

        $request->method = self::METHOD_PATCH;

        $responseObject = $request->request();

        return $responseObject;
    }


    /**
     * @param       $collection
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function getAllUri(
        $collection,
        $options = array()
    ) {
        $this->request         = new $this->client->requestClass();
        $this->request->client = $this->client;

        $request = $this->request;

        $request->options = $options;

        $request->path = $this->request->getDatabasePath() . self::API_DOCUMENT;
        $request->path .= '?collection=' . $collection;

        $request->method = self::METHOD_GET;

        $responseObject = $request->request();

        return $responseObject;
    }


    /**
     * @param string $handle The document handle of the document we want to get. Example: MyCollection/22334
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function get(
        $handle,
        $options = array()
    ) {
        $this->request         = new $this->client->requestClass();
        $this->request->client = $this->client;

        $request = $this->request;

        $request->options = $options;

        $request->path = $this->request->getDatabasePath() . self::API_DOCUMENT . '/' . $handle;


        $request->method = self::METHOD_GET;

        $responseObject = $request->request();

        return $responseObject;
    }


    /**
     * @param       $handle
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function delete(
        $handle,
        $options = array()
    ) {
        $this->request         = new $this->client->requestClass();
        $this->request->client = $this->client;

        $request = $this->request;

        $request->options = $options;

        $request->path   = $this->request->getDatabasePath() . self::API_DOCUMENT . '/' . $handle;
        $request->method = self::METHOD_DELETE;

        $responseObject = $request->request();

        return $responseObject;
    }
}
