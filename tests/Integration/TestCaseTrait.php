<?php
/**
 *
 * File: ArangoDbPhpCoreTestCase.php
 *
 * @package
 * @author Frank Mayer
 */

namespace frankmayer\ArangoDbPhpCore\Tests\Integration;


/**
 * Trait TestCaseTrait
 *
 * @package frankmayer\ArangoDbPhpCore
 */
trait TestCaseTrait
{
    /**
     *
     */
    public $TESTNAMES_PREFIX = 'ArangoDB-PHP-Core-';
    /**
     *
     */
    public $TESTS_NAMESPACE = '\\frankmayer\\ArangoDbPhpCore\\Tests\\';
}