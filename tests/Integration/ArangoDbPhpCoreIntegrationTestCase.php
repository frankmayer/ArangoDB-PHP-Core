<?php
/**
 *
 * File: ArangoDbPhpCoreTestCase.php
 *
 * @package
 * @author Frank Mayer
 */

namespace frankmayer\ArangoDbPhpCore;


/**
 * Class ArangoDbPhpCoreTestCase
 * @package frankmayer\ArangoDbPhpCore
 */
class ArangoDbPhpCoreIntegrationTestCase extends
    \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    const API_COLLECTION = '/_api/collection';
    /**
     *
     */
    const API_DOCUMENT   = '/_api/document';

    /**
     *
     */
    const METHOD_GET     = 'GET';
    /**
     *
     */
    const METHOD_POST    = 'POST';
    /**
     *
     */
    const METHOD_PUT     = 'PUT';
    /**
     *
     */
    const METHOD_PATCH   = 'PATCH';
    /**
     *
     */
    const METHOD_DELETE  = 'DELETE';
    /**
     *
     */
    const METHOD_HEAD    = 'HEAD';
    /**
     *
     */
    const METHOD_OPTIONS = 'OPTIONS';


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
     * Note: thanks Gabriel and Daniel for your initial idea/work. I only made it static ;)
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