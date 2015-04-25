<?php
/**
 * File: DocumentTrait.php
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2015, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Api\Rest;

use frankmayer\ArangoDbPhpCore\Protocols\Http\AbstractHttpRequest;


/**
 * Class EdgeTrait
 *
 * @package frankmayer\ArangoDbPhpCore\Api\Rest
 */
trait EdgeTrait
{
    /**
     * @param string $collection
     * @param string $from
     * @param string $to
     * @param string $body
     * @param array  $urlQuery
     * @param array  $options
     *
     * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
     */
    public function create(
        $collection,
        $from,
        $to,
        $body = null,
        $urlQuery = [],
        $options = []
    ) {
        /** @var AbstractHttpRequest $request */
        $request          = new $this->client->requestClass($this->client);
        $request->options = $options;
        $request->body    = $body;

        if (is_array($request->body)) {
            $request->body = json_encode($request->body);
        }

        $request->path = $this->client->fullDatabasePath . static::API_PATH;

        $urlQuery = array_merge($urlQuery ? $urlQuery : [],
            ['from' => $from, 'to' => $to],
            ['collection' => $collection]);

        $urlQuery = $request->buildUrlQuery($urlQuery);

        $request->path .= $urlQuery;

        $request->method = static::METHOD_POST;

        return $this->getReturnObject($request);
    }
}