<?php

/**
 * ArangoDB PHP Core Client: Response Interface
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequestInterface;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponseInterface;

//todo: Revisit those base classes and solve strict typing issues in classes (type hinting on parameters. This vs HttpResponseInterface).

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
     * @param HttpRequestInterface $request
     *
     * @return mixed
     */
    public function build(HttpRequestInterface $request): HttpResponseInterface;
}
