<?php
/**
 * File: DocumentBase.php
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Api\Rest;

use frankmayer\ArangoDbPhpCore\Protocols\Http\AbstractHttpRequest;


/**
 * Class DocumentBase
 *
 * @package frankmayer\ArangoDbPhpCore\Api\Rest
 */
class DocumentBase extends Api
{
    /**
     *
     */
    const API_PATH = '/_api/document';

    /**
     * @param string $handle
     * @param string $body
     * @param array  $urlQuery
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function replace($handle, $body, array $urlQuery = [], array $options = [])
    {
        /** @var AbstractHttpRequest $request */
        $request          = $this->getRequest();
        $request->options = $options;
        $request->body    = $body;

        if (is_array($request->body)) {
            $request->body = json_encode($request->body);
        }

        $request->path = $this->client->fullDatabasePath . static::API_PATH . '/' . $handle;

        $urlQueryStr = $request->buildUrlQuery($urlQuery);

        $request->path .= $urlQueryStr;

        $request->method = static::METHOD_PUT;

        return $this->getReturnObject($request);
    }

    /**
     * @param string $handle
     * @param string $body
     * @param array  $urlQuery
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function update($handle, $body, array $urlQuery = [], array $options = [])
    {
        /** @var AbstractHttpRequest $request */
        $request          = $this->getRequest();
        $request->options = $options;
        $request->body    = $body;

        if (is_array($request->body)) {
            $request->body = json_encode($request->body);
        }

        $request->path = $this->client->fullDatabasePath . static::API_PATH . '/' . $handle;

        $urlQueryStr = $request->buildUrlQuery($urlQuery);

        $request->path .= $urlQueryStr;

        $request->method = static::METHOD_PATCH;

        return $this->getReturnObject($request);
    }


    /**
     * @param string $collection
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function getAll($collection, array $options = [])
    {
        /** @var AbstractHttpRequest $request */
        $request          = $this->getRequest();
        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH;
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
    public function get($handle, array $options = [])
    {
        /** @var AbstractHttpRequest $request */
        $request          = $this->getRequest();
        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH . '/' . $handle;
        $request->method  = static::METHOD_GET;

        return $this->getReturnObject($request);
    }


    /**
     * @param string $handle The document handle of the document we want to get. Example: MyCollection/22334
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function getHeader($handle, array $options = [])
    {
        /** @var AbstractHttpRequest $request */
        $request          = $this->getRequest();
        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH . '/' . $handle;
        $request->method  = static::METHOD_HEAD;

        return $this->getReturnObject($request);
    }

    /**
     * @param string $handle
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function delete($handle, array $options = [])
    {
        /** @var AbstractHttpRequest $request */
        $request          = $this->getRequest();
        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH . '/' . $handle;
        $request->method  = static::METHOD_DELETE;

        return $this->getReturnObject($request);
    }
}
