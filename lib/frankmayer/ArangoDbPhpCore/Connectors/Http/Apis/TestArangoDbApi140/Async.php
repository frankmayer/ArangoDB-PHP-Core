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
 * An async functionality class for testing and demonstration purposes
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class Async extends
    Api implements
    RestApiInterface
{

    const API_JOB = '/_api/job';

    /**
     * @param string $handle The job handle of the job we want to get. Example: 1
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function fetchJobResult(
        $handle,
        $options = array()
    ) {
        $this->request         = new $this->client->requestClass();
        $this->request->client = $this->client;

        $request = $this->request;

        $request->options = $options;

        $request->path = $this->request->getDatabasePath() . self::API_JOB . '/' . $handle;

        $request->method = self::METHOD_PUT;

        $responseObject = $request->request();

        return $responseObject;
    }


    /**
     * @param string  $type The type of jobs to return. Might be `done` or `pending`. Example: 'pending'
     * @param integer $count
     * @param array   $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function listJobResults(
        $type,
        $count = null,
        $options = array()
    ) {
        $this->request         = new $this->client->requestClass();
        $this->request->client = $this->client;

        $request = $this->request;

        $request->options = $options;

        $request->path = $this->request->getDatabasePath() . self::API_JOB . '/' . $type;

        if ($count) {
            $urlQuery = array('count' => $count);

            $urlQuery = $this->request->buildUrlQuery($urlQuery);
        }

        $request->path .= $urlQuery;

        $request->method = self::METHOD_GET;

        $responseObject = $request->request();

        return $responseObject;
    }

    /**
     * @param mixed   $type The type or specific job id of jobs to delete. Example: 1 or `all` or `expired`
     * @param integer $stamp
     * @param array   $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpResponse
     */
    public function deleteJobResult(
        $type,
        $stamp = null,
        $options = array()
    ) {
        $this->request         = new $this->client->requestClass();
        $this->request->client = $this->client;

        $request = $this->request;

        $request->options = $options;

        $request->path = $this->request->getDatabasePath() . self::API_JOB . '/' . $type;

        if ($stamp) {

            $urlQuery = array('stamp' => $stamp);

            $urlQuery = $this->request->buildUrlQuery($urlQuery);
        }
        $request->path .= $urlQuery;

        $request->method = self::METHOD_DELETE;

        $responseObject = $request->request();

        return $responseObject;
    }
}
