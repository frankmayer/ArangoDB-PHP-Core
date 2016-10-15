<?php
/**
 * File: DocumentTrait.php
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2015, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Api\Rest;

use frankmayer\ArangoDbPhpCore\Protocols\Http\AbstractHttpRequest;


/**
 * Class DocumentTrait
 *
 * @package frankmayer\ArangoDbPhpCore\Api\Rest
 */
trait DocumentTrait
{
	/**
	 * @param string $collection
	 * @param string $body
	 * @param array  $urlQuery
	 * @param array  $options
	 *
	 * @return \frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse
	 */
	public function create(
		$collection = null,
		$body = null,
		array $urlQuery = [],
		array $options = []
	)
	{

		/** @var AbstractHttpRequest $request */
		$request          = $this->getRequest();
		$request->options = $options;
		$request->body    = $body;

		if (is_array($request->body))
		{
			$request->body = json_encode($request->body);
		}

		$request->path = $this->client->fullDatabasePath . static::API_PATH;

		if (null !== $collection)
		{
			$urlQuery = array_merge(
				$urlQuery ?: [],
				['collection' => $collection]
			);
		}

		$urlQueryStr = $request->buildUrlQuery($urlQuery);

		$request->path .= $urlQueryStr;

		$request->method = static::METHOD_POST;

		return $this->getReturnObject($request);
	}
}
