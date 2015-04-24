<?php

/**
 * ArangoDB PHP Core Client: Client Options
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore;

use frankmayer\ArangoDbPhpCore\Config\UpdatePolicy;


/**
 * Class ClientOptions
 *
 * @package frankmayer\ArangoDbPhpCore
 */
class ClientOptions
{
    /**
     * Endpoint string index constant
     */
    const OPTION_ENDPOINT = 'endpoint';

    /**
     * Authentication user name
     */
    const OPTION_AUTH_USER = 'AuthUser';

    /**
     * Authentication password
     */
    const OPTION_AUTH_PASSWD = 'AuthPasswd';

    /**
     * Authentication type
     */
    const OPTION_AUTH_TYPE = 'AuthType';

    /**
     * Timeout value index constant
     */
    const OPTION_TIMEOUT = 'timeout';

    /**
     * Batch flag
     */
    const OPTION_BATCH = 'Batch';

    /**
     * Batchpart flag
     */
    const OPTION_BATCHPART = 'BatchPart';

    /**
     * Client close or keepalive strategy constant
     */
    const OPTION_CLIENT = 'Close';

    /**
     * option to create unknown collections automatically
     */
    const OPTION_CREATE = false;

    /**
     * Update strategy
     */
    const OPTION_UPDATE_POLICY = UpdatePolicy::LAST;

    /**
     * UTF-8 CHeck Flag
     */
    const OPTION_CHECK_UTF8_CONFORM = 'CheckUtf8Conform';

    /**
     * Trace function index constant
     */
    const OPTION_TRACE = 'trace';

    /**
     * Enhanced trace
     */
    const OPTION_ENHANCED_TRACE = 'enhancedTrace';

    /**
     * Plugins index constant
     */
    const OPTION_PLUGINS = 'plugins';

    /**
     * Default database index constant
     */
    const OPTION_DEFAULT_DATABASE = 'defaultDatabase';

    /**
     * RequestClass index constant
     */
    const OPTION_REQUEST_CLASS = 'requestClass';

    /**
     * ResponseClass index constant
     */
    const OPTION_RESPONSE_CLASS = 'responseClass';

    /**
     * ResponseClass index constant
     */
    const OPTION_ARANGODB_API_VERSION = 'arangodbApiVersion';

    /**
     * ResponseClass index constant
     */
    const OPTION_DATABASE_PATH_PREFIX = 'databasePathPrefix';

}
