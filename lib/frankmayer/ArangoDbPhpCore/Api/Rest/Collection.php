<?php
/**
 * ArangoDB PHP Core Client: client
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2015, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Api\Rest;

use frankmayer\ArangoDbPhpCore\Api\RestApiInterface;
use frankmayer\ArangoDbPhpCore\Protocols\Http\AbstractHttpRequest;


/**
 * Class Collection
 *
 * @package frankmayer\ArangoDbPhpCore\Api\Rest
 */
class Collection extends Api implements RestApiInterface
{
    /**
     *
     */
    const API_PATH = '/_api/collection';

    /**
     * @param string $collectionName       The collection name
     * @param array  $collectionParameters Collection parameters according to the HTTP API
     * @param array  $options              Other options (not stable yet...)
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     * @throws \frankmayer\ArangoDbPhpCore\ClientException
     */
    public function create($collectionName, array $collectionParameters = [], array $options = [])
    {
        $request = $this->getRequest();

        $request->options = $options;
        $request->body    = ['name' => $collectionName];

        $request->body = static::array_merge_recursive_distinct($request->body, $collectionParameters);
        $request->body = json_encode($request->body);

        $request->path   = $this->client->fullDatabasePath . static::API_PATH;
        $request->method = static::METHOD_POST;

        return $this->getReturnObject($request);
    }


    /**
     * @param string $collectionName
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function drop($collectionName, array $options = [])
    {
        /** @var AbstractHttpRequest $request */
        $request = $this->getRequest();

        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH . '/' . $collectionName;
        $request->method  = static::METHOD_DELETE;

        return $this->getReturnObject($request);
    }


    /**
     * @param string $collectionName
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function truncate($collectionName, array $options = [])
    {
        /** @var AbstractHttpRequest $request */
        $request = $this->getRequest();

        $request->options = $options;

        $request->path   = $this->client->fullDatabasePath . static::API_PATH . '/' . $collectionName . '/truncate';
        $request->method = static::METHOD_PUT;

        return $this->getReturnObject($request);
    }


    /**
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function getAll(array $options = [])
    {
        /** @var AbstractHttpRequest $request */
        $request = $this->getRequest();

        $request->options = $options;

        $request->path = $this->client->fullDatabasePath . static::API_PATH;
        if (isset($request->options['excludeSystem']) && $request->options['excludeSystem'] === true) {
            $request->path .= '?excludeSystem=true';
        }

        $request->method = static::METHOD_GET;

        return $this->getReturnObject($request);
    }
}
