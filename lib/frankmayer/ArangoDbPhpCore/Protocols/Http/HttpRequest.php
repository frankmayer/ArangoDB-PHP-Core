<?php

/**
 * ArangoDB PHP Core Client: HTTP Request
 *
 * @author    Frank Mayer
 * @copyright Copyright 2013-2015, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCore\Protocols\Http;


	/**
	 * HTTP-Request object that holds a request. Requests are in some cases not directly passed to the server,
	 * for instance when a request is destined for a batch.
	 *
	 * @package frankmayer\ArangoDbPhpCore\Protocols\Http
	 */
/**
 * Class HttpRequest
 *
 * @package frankmayer\ArangoDbPhpCore\Protocols\Http
 */
class HttpRequest extends
	AbstractHttpRequest
{
	/**
	 * @var boolean
	 * todo: is this still needed?
	 */
	public $isBatchPart202;


	/**
	 * Method to send an HTTP request.
	 * All request should be done through this method. Any async or batch handling is done within this method.
	 *
	 * @return HttpResponse Http Response object
	 */
	public function send()
	{
		$this->client->notifyPlugins('beforeRequest', [$this]);
		if (isset($this->options['async']))
		{
			$async = $this->options['async'];
			if (is_bool($async))
			{
				$async = var_export($async, true);
			}
			$this->headers['x-arango-async'] = $async;
		}

		if (isset($this->options['isBatchPart']) && $this->options['isBatchPart'] === true)
		{
			//            $this->isBatchPart = true;
			$this->address = $this->client->endpoint . $this->path;

			return true;
		}
		else
		{
			if (null !== $this->options && (!array_key_exists(
						'isBatchRequest',
						$this->options
					) || (isset($this->options['isBatchRequest']) && $this->options['isBatchRequest'] !== true))
			)
			{
				$this->headers['Content-Type'] = 'application/json';
			}
			$this->address  = $this->client->endpoint . $this->path;
			$this->response = $this->client->connector->request($this);
		}

		return $this->client->doRequest($this);
	}


	/**
	 * @param array  $batchParts
	 * @param string $boundary
	 *
	 * @return HttpResponseInterface
	 */
	public function sendBatch(
		array $batchParts = [],
		$boundary = 'XXXbXXX'
	)
	{
		$connector  = $this->client->connector;
		$this->body = '';
		/** @var $batchPart AbstractHttpRequest */
		// Reminder... The reason, that at this time the batch-parts are HttpResponses is, because of the quasi "promise" that we have to return immediately
		foreach ($batchParts as $contentId => $batchPart)
		{
			$this->body .= '--' . $boundary . $connector::HTTP_EOL;
			$this->body .= 'Content-Type: application/x-arango-batchpart' . $connector::HTTP_EOL;
			$this->body .= 'Content-Id: ' . ($contentId + 1) . $connector::HTTP_EOL;

			$this->body .= $connector::HTTP_EOL;
			$this->body .= strtoupper($batchPart->method) . ' ' . $batchPart->path
				. ' ' . 'HTTP/1.1' . $connector::HTTP_EOL . $connector::HTTP_EOL;
			$this->body .= $batchPart->body . $connector::HTTP_EOL;
		}
		$this->body .= '--' . $boundary . '--' . $connector::HTTP_EOL;
		$this->path                    = $this->client->fullDatabasePath . self::API_BATCH;
		$this->headers['Content-Type'] = 'multipart/form-data; ' . $boundary;

		$this->method         = 'post';
		$this->batch          = true;
		$this->batchBoundary  = $boundary;
		$this->batchParts     = $batchParts;
		$this->responseObject = $this->send();

		return $this->responseObject;
	}


	/**
	 * @param string $address
	 */
	public function setAddress($address)
	{
		$this->address = $address;
	}

	/**
	 * @return string
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * @param string $body
	 */
	public function setBody($body)
	{
		$this->body = $body;
	}

	/**
	 * @return string
	 */
	public function getBody()
	{
		return $this->body;
	}

	/**
	 * @param \frankmayer\ArangoDbPhpCore\Client $client
	 */
	public function setClient($client)
	{
		$this->client = $client;
	}

	/**
	 * @return \frankmayer\ArangoDbPhpCore\Client
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * @param \frankmayer\ArangoDbPhpCore\ConnectorInterface $connector
	 */
	public function setConnector($connector)
	{
		$this->connector = $connector;
	}

	/**
	 * @return \frankmayer\ArangoDbPhpCore\ConnectorInterface
	 */
	public function getConnector()
	{
		return $this->connector;
	}

	/**
	 * @param array $headers
	 */
	public function setHeaders($headers)
	{
		$this->headers = $headers;
	}

	/**
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * @param string $method
	 */
	public function setMethod($method)
	{
		$this->method = $method;
	}

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * @param array $options
	 */
	public function setOptions($options)
	{
		$this->options = $options;
	}

	/**
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * @param string $path
	 */
	public function setPath($path)
	{
		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @param string $response
	 */
	public function setResponse($response)
	{
		$this->response = $response;
	}

	/**
	 * @return string
	 */
	public function getResponse()
	{
		return $this->response;
	}
	// todo 1 Frank Revisit this method and getter/setter

	//    /**
	//     * @param \frankmayer\ArangoDbPhpCore\Connectors\ResponseInterface $responseObject
	//     */
	//    public function setResponseObject($responseObject)
	//    {
	//        $this->responseObject = $responseObject;
	//    }
	//
	//    /**
	//     * @return \frankmayer\ArangoDbPhpCore\Connectors\ResponseInterface
	//     */
	//    public function getResponseObject()
	//    {
	//        return $this->responseObject;
	//    }
	// todo 1 Frank Revisit this method and getter/setter
	//    /**
	//     * @param string $type
	//     */
	//    public function setType($type)
	//    {
	//        $this->type = $type;
	//    }
	//
	//    /**
	//     * @return string
	//     */
	//    public function getType()
	//    {
	//        return $this->type;
	//    }
}
