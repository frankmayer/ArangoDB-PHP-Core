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
 * A batch functionality class for testing and demonstration purposes
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class Batch extends
    Api implements
    RestApiInterface
{


    public function send($batchParts)
    {
        /** @var Request $request */
        $request         = new $this->client->requestClass();
        $request->client = $this->client;

        return $request->sendBatch($batchParts);
    }
}