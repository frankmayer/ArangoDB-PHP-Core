<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Performance Test (Internal)
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;

// todo 1 Frank Clean up this mess of tests that got piled up through the nights... :D
use frankmayer\ArangoDbPhpCore\Connectors\Http\Apis\TestArangoDbApi140 as ArangoDbApi;

use frankmayer\ArangoDbPhpCore\Connectors\Http\CurlHttpConnector;
use frankmayer\ArangoDbPhpCore\Plugins\TracerPlugin;


class testConnClass
{
    public $pubVar;
}


class TestClass
{
    public $pubVar;
    public $pubVarArray;
    public $var2;
    public $var3;
    public $var4;
    public $var5;


    public function testArray($array)
    {
        return is_array($array);
    }

    static function staticTest($connClass, $var)
    {
        $connClass->pubVar = $var;

        return $var;
    }

    static function staticTest2($connClass, $var, $var2, $var3)
    {
        $connClass->pubVarArray[]  = $var;
        $connClass->pubVar2Array[] = $var2;
        $connClass->pubVar3Array[] = $var3;

        return $var;
    }

    function test($connClass, $var)
    {

        $this->pubVar = $var;

        return $var;
    }
}


class TestClass2 extends
    TestClass
{

    public function testArray($array)
    {
        return (array) $array !== $array ? false : true;
    }
}


class TestClass3 extends
    TestClass
{

    public function testArray($array)
    {
        return (array) $array !== $array ? false : true;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        throw new ClientException('Property doesn\'t exist');
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}


class PerformanceTest extends
    \PHPUnit_Framework_TestCase
{
    protected $client;
    protected $clientOptions;
    protected $startTime;


    public function setUp()
    {

        $connector    = new CurlHttpConnector();
        $this->client = $this->client = getClient($connector);

        error_reporting(E_ALL);
        //        $count = 100000;

        //        $connClass = new testConnClass();
        //        echo PHP_EOL . PHP_EOL . 'Count: ' . $count . PHP_EOL;
        $this->startTime = microtime(true);
        //        $this->client = getClient();
        //        $this->adminHandler = new AdminHandler($this->client);
    }


    /**
     * Test if we can get the server version
     */
    public function test1()
    {
        //        exit;
        echo PHP_EOL . 'test1' . PHP_EOL;
        echo 'Memory usage before: ' . number_format(memory_get_usage()) . ' bytes ' . PHP_EOL;


        $tracer            = new TracerPlugin();
        $tracer->priority  = 0;
        $tracer2           = new TracerPlugin();
        $tracer2->priority = 20;
        $tracer3           = new TracerPlugin();
        $tracer3->priority = -30;

        $this->clientOptions['plugins']                  = array(
            'tracer1' => $tracer,
            'tracer2' => $tracer2,
            'tracer3' => $tracer3
        );
        $this->clientOptions['PluginManager']['options'] = array();
        $this->clientOptions['PluginManager']['options'] = array();


        $this->client->traceThis();
        $tracer3->priority = 30;
        $this->client->setPluginsFromPluginArray(array('tracer3' => $tracer3));
        $this->client->traceThis();
    }


    /**
     * Test if we can get the server version
     */
    public function test2()
    {

        $this->client->traceThis();
        $this->client->traceThis();
    }

    /**
     * Test if we can get the server version
     */
    public function testBenchmark()
    {

        $count = 1;

        $connClass = new testConnClass();
        echo PHP_EOL . PHP_EOL . 'Count: ' . $count . PHP_EOL;

        echo PHP_EOL . 'Static execution' . PHP_EOL;
        echo 'Memory usage before: ' . number_format(memory_get_usage()) . ' bytes ' . PHP_EOL;
        $this->startTime = microtime(true);

        for ($i = 0; $i < $count; $i++) {
            TestClass::staticTest($connClass, $i);
        }

        echo 'Process time: ' . (microtime(true) - $this->startTime) . ' ms ' . PHP_EOL;
        echo 'Memory usage after: ' . number_format(memory_get_usage()) . ' bytes ' . PHP_EOL;
        echo '' . PHP_EOL;

        echo PHP_EOL . 'Static execution2' . PHP_EOL;
        echo 'Memory usage before: ' . number_format(memory_get_usage()) . ' bytes ' . PHP_EOL;
        $this->startTime = microtime(true);

        for ($i = 0; $i < $count; $i++) {
            TestClass::staticTest2($connClass, $i, 'a', 'b');
        }

        echo 'Process time: ' . (microtime(true) - $this->startTime) . ' ms ' . PHP_EOL;
        echo 'Memory usage after: ' . number_format(memory_get_usage()) . ' bytes ' . PHP_EOL;
        echo '' . PHP_EOL;

        echo 'Instanced execution - declared properties' . PHP_EOL;
        echo 'Memory usage before: ' . number_format(memory_get_usage()) . ' bytes ' . PHP_EOL;
        $this->startTime = microtime(true);

        for ($i = 0; $i < $count; $i++) {
            $obj = new TestClass();
            $obj->test($connClass, $i);
            $obj->testArray(array('a', 'b'));

            $obj->var2 = 'a';
            $obj->var3 = 'b';
        }
        echo 'Process time: ' . (microtime(true) - $this->startTime) . ' ms ' . PHP_EOL;
        echo 'Memory usage after: ' . number_format(memory_get_usage()) . ' bytes ' . PHP_EOL;
        echo '' . PHP_EOL;


        echo 'Instanced execution - non declared properties' . PHP_EOL;
        echo 'Memory usage before: ' . number_format(memory_get_usage()) . ' bytes ' . PHP_EOL;
        $this->startTime = microtime(true);

        for ($i = 0; $i < $count; $i++) {
            $obj = new TestClass();
            $obj->test($connClass, $i);
            $obj->testArray(array('a', 'b'));

            $obj->var2 = 'a';
            $obj->var3 = 'b';
        }

        echo 'Process time: ' . (microtime(true) - $this->startTime) . ' ms ' . PHP_EOL;
        echo 'Memory usage after: ' . number_format(memory_get_usage()) . ' bytes ' . PHP_EOL;
        echo '' . PHP_EOL;

        echo 'Instanced execution - magic methods' . PHP_EOL;
        echo 'Memory usage before: ' . number_format(memory_get_usage()) . ' bytes ' . PHP_EOL;
        $this->startTime = microtime(true);

        for ($i = 0; $i < $count; $i++) {
            $obj = new TestClass();
            $obj->test($connClass, $i);
            $obj->var4 = 'a';
            $obj->var5 = 'b';
        }

        echo 'Process time: ' . (microtime(true) - $this->startTime) . ' ms ' . PHP_EOL;
        echo 'Memory usage after: ' . number_format(memory_get_usage()) . ' bytes ' . PHP_EOL;
        echo '' . PHP_EOL;
    }


    public function tearDown()
    {
        echo 'Process time: ' . (microtime(true) - $this->startTime) . ' ms ' . PHP_EOL;
        echo 'Memory usage after: ' . number_format(memory_get_usage()) . ' bytes ' . PHP_EOL;
        echo '' . PHP_EOL;
        //        unset($this->adminHandler);
        //        unset($this->client);
    }
}
