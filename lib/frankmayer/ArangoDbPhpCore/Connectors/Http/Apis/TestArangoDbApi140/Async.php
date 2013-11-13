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
 * Provides access to the ArangoDB server
 * As all access is done using HTTP, we do not need to establish a
 * persistent client and keep its state.
 * Instead, clients are established on the fly for each request
 * and are destroyed afterwards.
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class Async extends
    Api implements
    RestApiInterface
{

    public function fetchJobResult($jobId)
    {
        $this->request = new $this->requestClass($this->client);
        $request       = $this->request;
        //        return $request->sendBatch();
    }


    public function deleteJobResult($jobId)
    {
        $this->request = new $this->requestClass($this->client);
        $request       = $this->request;
        //        return $request->sendBatch();
    }

    public function listJobResults($count, $type)
    {
        $this->request = new $this->requestClass($this->client);
        $request       = $this->request;
        //        return $request->sendBatch();
    }
}
