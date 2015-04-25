<?php

/**
 * ArangoDB PHP Core Client: Response Interface
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2015, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols;

/**
 * A Response Interface
 *
 * @package frankmayer\ArangoDbPhpCore\Protocols
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
