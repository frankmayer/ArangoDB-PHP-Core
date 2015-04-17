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
     * @param        $client
     * @param string $handle The job handle of the job we want to get. Example: 1
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public static function fetchJobResult($client, $handle, $options = [])
    {
        /** @var Request $request */
        $request          = new $client->requestClass();
        $request->client  = $client;
        $request->options = $options;
        $request->path    = $request->getDatabasePath() . self::API_JOB . '/' . $handle;
        $request->method  = self::METHOD_PUT;

        $responseObject = $request->send();

        return $responseObject;
    }


    /**
     * @param string  $type The type of jobs to return. Might be `done` or `pending`. Example: 'pending'
     * @param integer $count
     * @param array   $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public static function listJobResults($client, $type, $count = null, $options = [])
    {
        $urlQuery = null;

        /** @var Request $request */
        $request          = new $client->requestClass();
        $request->client  = $client;
        $request->options = $options;
        $request->path    = $request->getDatabasePath() . self::API_JOB . '/' . $type;

        if ($count) {
            $urlQuery = ['count' => $count];
            $urlQuery = $request->buildUrlQuery($urlQuery);
        }

        $request->path .= $urlQuery;

        $request->method = self::METHOD_GET;

        $responseObject = $request->send();

        return $responseObject;
    }

    /**
     * @param         $client
     * @param mixed   $type The type or specific job id of jobs to delete. Example: 1 or `all` or `expired`
     * @param integer $stamp
     * @param array   $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\Response
     */
    public static function deleteJobResult($client, $type, $stamp = null, $options = [])
    {
        $urlQuery = null;

        /** @var Request $request */
        $request          = new $client->requestClass();
        $request->client  = $client;
        $request->options = $options;
        $request->path    = $request->getDatabasePath() . self::API_JOB . '/' . $type;

        if ($stamp) {
            $urlQuery = ['stamp' => $stamp];
            $urlQuery = $request->buildUrlQuery($urlQuery);
        }
        $request->path .= $urlQuery;

        $request->method = self::METHOD_DELETE;

        $responseObject = $request->send();

        return $responseObject;
    }
}
