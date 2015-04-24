<?php

/**
 * ArangoDB PHP Core Client: Response Interface
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols;

/**
 * A Response Interface
 *
 * @package frankmayer\ArangoDbPhpCoreCore
 */
interface ResponseInterface
{
    /**
     * Build a response-object from the request-object, which holds the result of the executed request
     *
     * @param $request
     *
     * @return mixed
     */
    public function build($request);
}
