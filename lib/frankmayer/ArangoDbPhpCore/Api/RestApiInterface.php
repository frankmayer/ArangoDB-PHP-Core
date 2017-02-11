<?php
/**
 * ArangoDB PHP Core Client: client
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Api;

use frankmayer\ArangoDbPhpCore\Protocols\RequestInterface;
use frankmayer\ArangoDbPhpCore\Protocols\ResponseInterface;


/**
 * A REST API Interface
 *
 * @package   frankmayer\ArangoDbPhpCore\Api
 */
interface  RestApiInterface extends ApiInterface
{
    /**
     * @return RequestInterface
     */
    public function getRequest();

    /**
     * @return ResponseInterface
     */
    public function getResponse();
}
