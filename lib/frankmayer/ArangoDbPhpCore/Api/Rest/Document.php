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
use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\ClientInterface;
use frankmayer\ArangoDbPhpCore\Protocols\Http\AbstractHttpRequest;


/**
 * A document class for testing and demonstration purposes
 *
 * @property  ClientInterface|Client $client
 * @package frankmayer\ArangoDbPhpCore
 */
class Document extends
    DocumentBase implements
    RestApiInterface
{
    use DocumentTrait;
    /**
     *
     */
    const API_PATH = '/_api/document';



}
