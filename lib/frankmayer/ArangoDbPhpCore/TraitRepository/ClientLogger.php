<?php

/**
 * ArangoDB PHP Core Client: Client Logger Trait (for testing)
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\TraitRepository;


trait ClientLogger
{
    public function log($logData)
    {
        echo "LOGGING: " . $logData;

        return true;
    }
}
