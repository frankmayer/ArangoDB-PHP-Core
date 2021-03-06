<?php
/**
 * ArangoDB PHP Core Client: client
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Api\Rest;

use frankmayer\ArangoDbPhpCore\Api\RestApiInterface;


/**
 * Class Edge
 *
 * @package frankmayer\ArangoDbPhpCore\Api\Rest
 */
class Edge extends DocumentBase implements RestApiInterface
{
    use EdgeTrait;

    /**
     *
     */
    const API_PATH = '/_api/edge';
}
