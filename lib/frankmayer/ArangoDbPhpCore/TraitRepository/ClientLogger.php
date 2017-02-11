<?php

/**
 * ArangoDB PHP Core Client: Client Logger Trait (for testing)
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2017, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\TraitRepository;


/**
 * Class ClientLogger
 *
 * @package frankmayer\ArangoDbPhpCore\TraitRepository
 */
trait ClientLogger
{
    /**
     * @param $logData
     *
     * @return bool
     */
    public function log($logData)
    {
        echo 'LOGGING: ' . $logData;

        return true;
    }
}
