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
 * An async functionality class for testing and demonstration purposes
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class Async extends
    Api implements
    RestApiInterface
{
    const API_JOB = '/_api/job';

    public $client;


    /**
     * @param string $handle The job handle of the job we want to get. Example: 1
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function fetchJobResult(
        $handle,
        $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->path    = $request->getDatabasePath() . static::API_JOB . '/' . $handle;
        $request->method  = static::METHOD_PUT;

        return $this->getReturnObject($request);
    }


    /**
     * @param string  $type The type of jobs to return. Might be `done` or `pending`. Example: 'pending'
     * @param integer $count
     * @param array   $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function listJobResults(
        $type,
        $count = null,
        $options = []
    ) {
        $urlQuery = null;

        /** @var AbstractHttpRequest $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->path    = $request->getDatabasePath() . static::API_JOB . '/' . $type;

        if ($count) {
            $urlQuery = ['count' => $count];
            $urlQuery = $request->buildUrlQuery($urlQuery);
        }

        $request->path .= $urlQuery;

        $request->method = static::METHOD_GET;

        return $this->getReturnObject($request);
    }


    /**
     * @param mixed   $type The type or specific job id of jobs to delete. Example: 1 or `all` or `expired`
     * @param integer $stamp
     * @param array   $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function deleteJobResult(
        $type,
        $stamp = null,
        $options = []
    ) {
        $urlQuery = null;

        /** @var AbstractHttpRequest $request */
        $request          = new $this->client->requestClass();
        $request->client  = $this->client;
        $request->options = $options;
        $request->path    = $request->getDatabasePath() . static::API_JOB . '/' . $type;

        if ($stamp) {
            $urlQuery = ['stamp' => $stamp];
            $urlQuery = $request->buildUrlQuery($urlQuery);
        }
        $request->path .= $urlQuery;

        $request->method = static::METHOD_DELETE;

        return $this->getReturnObject($request);
    }
}