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
     * @param string $logData
     *
     * @return bool
     */
    public function log(string $logData): bool
    {
        echo 'LOGGING: ' . $logData;

        return true;
    }
}
