<?php
/**
 * ArangoDB PHP Core Client: client
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
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
     *
     * @codeCoverageIgnore There is no unit-test for this ATM. However, the functionality is tested by integration tests from higher level clients like Core-Guzzle
     */
    public static function send($client, $batchParts)
    {
        /** @var AbstractHttpRequest $request */
        $request         = new $client->requestClass($client);
        $request->client = $client;

        return $request->sendBatch($batchParts);
    }
}
