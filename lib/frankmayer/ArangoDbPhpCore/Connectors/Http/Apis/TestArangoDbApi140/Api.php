<?php

/**
 * ArangoDB PHP Core Client: client
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140;

use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest;


/**
 * A base API class for testing and demonstration purposes
 *
 * @package frankmayer\ArangoDbPhpCore
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

    public $address;
    public $client;
    public $headers;
    public $body;
    public $path;
    public $options;
    public $method;

    /**
     * @var HTTPRequest
     */
    public $request;
    /**
     * @var HTTPRequest
     */
    public $requestClass;

    /**
     * @param \frankmayer\ArangoDbPhpCore\Client $client
     *
     */
    public function __construct()
    {
//        $this->client       = $client;
//        $this->connector    = $this->client->connector;
//        $this->requestClass = $this->client->requestClass;
    }

    //    /**
    //     * @param Client $client
    //     *
    //     * @return HttpRequest
    //     */
    //    public static function instantiateRequestObject(Client $client){
    //        return $client->getConnector()->instantiateRequestObject($client);
    //    }
    //
    //    /**
    //     * @param \frankmayer\ArangoDbPhpCore\Connectors\Http\HttpRequest $request
    //     *
    //     * @return HttpRequest
    //     */
    //    public static function instantiateResponseObject(HttpRequest $request){
    //        return $request->client->getConnector()->instantiateResponseObject($request);
    //    }

    public function buildUrlQuery($urlQueryArray)
    {
        $params = array();
        foreach ($urlQueryArray as $key => $value) {
            $params[] = $key . '=' . $value;
        }

        return '?' . implode('&', $params);
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
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     * @author Frank Mayer
     */
    public static function array_merge_recursive_distinct(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset ($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = self::array_merge_recursive_distinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
    }
}
