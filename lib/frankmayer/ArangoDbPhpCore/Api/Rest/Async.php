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
 * Class Async
 *
 * @package frankmayer\ArangoDbPhpCore\Api\Rest
 */
class Async extends Api implements RestApiInterface
{
    /**
     *
     */
    const API_PATH = '/_api/job';

    /**
     * @param string $handle The job handle of the job we want to get. Example: 1
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function fetchJobResult($handle, array $options = [])
    {
        /** @var AbstractHttpRequest $request */
        $request          = $this->getRequest();
        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH . '/' . $handle;
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
    public function listJobResults($type, $count = null, array $options = [])
    {
        $urlQuery = null;

        /** @var AbstractHttpRequest $request */
        $request          = $this->getRequest();
        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH . '/' . $type;
        $urlQueryStr      = '';

        if ($count) {
            $urlQuery    = ['count' => $count];
            $urlQueryStr = $request->buildUrlQuery($urlQuery);
        }

        $request->path .= $urlQueryStr;

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
    public function deleteJobResult($type, $stamp = null, array $options = [])
    {
        $urlQuery = null;

        /** @var AbstractHttpRequest $request */
        $request          = $this->getRequest();
        $request->options = $options;
        $request->path    = $this->client->fullDatabasePath . static::API_PATH . '/' . $type;
        $urlQueryStr      = '';

        if ($stamp) {
            $urlQuery    = ['stamp' => $stamp];
            $urlQueryStr = $request->buildUrlQuery($urlQuery);
        }
        $request->path .= $urlQueryStr;

        $request->method = static::METHOD_DELETE;

        return $this->getReturnObject($request);
    }
}
