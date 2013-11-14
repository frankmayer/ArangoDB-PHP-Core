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
 * A batch functionality class for testing and demonstration purposes
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class Batch extends
    Api implements
    RestApiInterface
{

    public function send()
    {
        $this->request = new $this->requestClass($this->client);
        $request       = $this->request;

        return $request->sendBatch();
    }
}
