<?php
/**
 * ArangoDB PHP Core Client: client
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2015, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Api\Rest;

use frankmayer\ArangoDbPhpCore\Api\RestApiInterface;


/**
 * Class Document
 *
 * @package frankmayer\ArangoDbPhpCore\Api\Rest
 */
class Document extends
    DocumentBase implements
    RestApiInterface
{
    use DocumentTrait;
}
