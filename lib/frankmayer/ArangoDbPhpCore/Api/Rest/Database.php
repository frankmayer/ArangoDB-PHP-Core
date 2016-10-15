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
 * Class Database
 *
 * @package frankmayer\ArangoDbPhpCore\Api\Rest
 */
class Database extends
    Api implements
    RestApiInterface
{
    /**
     *
     */
    const API_PATH = '/_api/database';

    /**
     * @param string $databaseName
     * @param array  $databaseParameters
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     * @throws \frankmayer\ArangoDbPhpCore\ClientException
     */
    public function create(
        $databaseName,
        array $databaseParameters = [],
        array $options = []
    ) {
        $request = $this->getRequest();

        $request->options = $options;
        $request->body    = ['name' => $databaseName];

        $request->body = static::array_merge_recursive_distinct($request->body, $databaseParameters);
        $request->body = json_encode($request->body);

        $request->path   = $this->client->fullDatabasePath . static::API_PATH;
        $request->method = static::METHOD_POST;

        return $this->getReturnObject($request);
    }


    /**
     * @param string $databaseName
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function drop(
        $databaseName,
        array $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request = $this->getRequest();

        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH . '/' . $databaseName;
        $request->method  = static::METHOD_DELETE;

        return $this->getReturnObject($request);
    }


    /**
     * @param string $databaseName
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function truncate(
        $databaseName,
        array $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request = $this->getRequest();

        $request->options = $options;

        $request->path   = $this->client->fullDatabasePath . static::API_PATH . '/' . $databaseName . '/truncate';
        $request->method = static::METHOD_PUT;

        return $this->getReturnObject($request);
    }


    /**
     * @param array $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function getAll(
        array $options = []
    ) {
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
