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
 * A batch functionality class for testing and demonstration purposes
 *
 * @package   frankmayer\ArangoDbPhpCore\Api\Rest
 */
class Batch extends Api implements RestApiInterface
{
    /**
     * @param $client
     * @param $batchParts
     *
     * @return mixed
     */
    public static function send($client, $batchParts)
    {
        /** @var AbstractHttpRequest $request */
        $request         = new $client->requestClass($client);
        $request->client = $client;

        return $request->sendBatch($batchParts);
    }
}
