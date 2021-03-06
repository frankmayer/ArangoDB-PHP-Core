<?php
/**
 * ArangoDB PHP Core Client: client
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Api\Rest;

use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\Protocols\Http\AbstractHttpRequest;
use frankmayer\ArangoDbPhpCore\Protocols\ResponseInterface;


/**
 * Class Api
 *
 * @package frankmayer\ArangoDbPhpCore\Api\Rest
 */
class Api
{
    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_PATCH   = 'PATCH';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_HEAD    = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';

    /**
     * @var Client The client instance of this api instance
     */
    public $client;


    /**
     * Constructs an api object through which commands can be issued against the server.
     * For each API class, only one instance per Client is necessary.
     *
     * @param $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }


    /**
     * Returns the correct object, according to the 'isBatchPart' option.
     * If it is a Batchpart, it returns the request object, else the response.
     *
     * @param AbstractHttpRequest $request
     *
     * @return mixed
     */
    public function getReturnObject($request)
    {
        if (array_key_exists('isBatchPart', $request->options) && $request->options['isBatchPart'] === true) {
            return $request;
        } else {
            return $request->send();
        }
    }


    /**
     * @return AbstractHttpRequest
     */
    public function getRequest()
    {
        return $this->client->getRequest();
    }


    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->client->getResponse();
    }


    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * array_merge_recursive_distinct does not change the data-types of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     *
     * Note: thanks Gabriel and Daniel for your work. I only made it static ;)
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     *
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     * @author Frank Mayer
     */
    public static function array_merge_recursive_distinct(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset ($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = static::array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

}
