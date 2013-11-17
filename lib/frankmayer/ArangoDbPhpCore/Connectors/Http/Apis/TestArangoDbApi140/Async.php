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
    public function __construct($client)
    {
        $this->client       = $client;
        $this->connector    = $this->client->connector;
        $this->requestClass = $this->client->requestClass;
    }

    public function fetchJobResult($jobId)
    {
        $this->request = new $this->requestClass();
        $this->request->client=$this->client;
        //        $request       = $this->request;
        //        return $request->sendBatch();
    }


    public function deleteJobResult($jobId)
    {
        $this->request = new $this->requestClass();
        $this->request->client=$this->client;
        //        $request       = $this->request;
        //        return $request->sendBatch();
    }

    public function listJobResults($count, $type)
    {
        $this->request = new $this->requestClass();
        $this->request->client=$this->client;
        //        $request       = $this->request;
        //        return $request->sendBatch();
    }
}
