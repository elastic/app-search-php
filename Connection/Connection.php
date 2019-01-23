<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Connection;

use GuzzleHttp\Ring\Core;
use Psr\Log\LoggerInterface;
use Swiftype\AppSearch\Serializer\SerializerInterface;
use \Swiftype\AppSearch\Exception\ConnectionException;
use \Swiftype\AppSearch\Exception\CouldNotResolveHostException;
use \Swiftype\AppSearch\Exception\CouldNotConnectToHostException;
use \Swiftype\AppSearch\Exception\OperationTimeoutException;

/**
 * Connection bring HTTP connectivity to the Swiftype HTTP API.
 *
 * @package Swiftype\AppSearch\Connection
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class Connection
{
    /**
     * @var callable
     */
    private $handler;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var array
     */
    private $connectionParams = [
        'uriPrefix' => '/api/as/v1',
    ];

    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * @var LoggerInterface
     */
    private $tracer;

    /**
     * Constructor.
     *
     * @param callable            $handler          Guzzle handler used to issue request.
     * @param SerializerInterface $serializer       JSON serializer.
     * @param LoggerInterface     $logger           Logger used for warning & error.
     * @param LoggerInterface     $tracer           Logger used for tracing.
     * @param array               $connectionParams Connections params.
     */
    public function __construct(
        callable $handler,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        LoggerInterface $tracer,
        $connectionParams = []
    ) {
        $this->handler    = $handler;
        $this->serializer = $serializer;
        $this->logger     = $logger;
        $this->tracer     = $tracer;

        $this->connectionParams = array_merge($this->connectionParams, $connectionParams);
    }

    /**
     * Run the HTTP request and process the result to be usable by the client.
     *
     * @param string     $method  HTTP method (eg. GET, POST, ...).
     * @param string     $uri     URI of the request.
     * @param array|null $params  Query params.
     * @param array|null $body    Request body.
     * @param array|null $options Additional options.
          *
     * @return array
     */
    public function performRequest($method, $uri, $params = null, $body = null, $options = [])
    {
        if (in_array($method, ['PUT', 'POST']) && $params !== null) {
            $body   = $body ? array_merge($body, $params) :$params;
            $params = null;
        }

        if (isset($body) === true) {
            $body = $this->serializer->serialize($body);
            $options['headers']['Content-Type'][] = 'application/json';
        }

        $request = [
            'http_method' => $method,
            'uri'         => $this->getURI($uri, $params),
            'body'        => $body,
        ];

        $request = array_replace_recursive($request, $this->connectionParams);

        if (!empty($options)) {
            $request = array_replace_recursive($request, $options);
        }

        if (empty($request['client'])) {
            unset($request['client']);
        }

        $handler = $this->wrapHandler($this->handler);

        return $handler($request);
    }

    /**
     * Build request URI from basepath and query params.
     *
     * @param string     $uri    URI of the request.
     * @param array|null $params Query params.
     *
     * @return string
     */
    private function getURI($uri, $params)
    {
        if (!empty($params)) {
            array_walk($params, function (&$value, &$key) {
                if ($value === true) {
                    $value = 'true';
                } elseif ($value === false) {
                    $value = 'false';
                }
            });

                $uri .= '?' . http_build_query($params);
        }

        return sprintf("%s/%s", $this->connectionParams['uriPrefix'], $uri);
    }

    /**
     * Install proxy method that wrap the original handler to postprocess the response.
     *
     * @param callable $handler Original handler.
     *
     * @return callable
     */
    private function wrapHandler(callable $handler)
    {
        $handler = function (array $request) use ($handler) {
            $response =  Core::proxy($handler($request), function ($response) use ($request) {
                if (isset($response['error']) === true) {
                    throw $this->getConnectionErrorException($request, $response);
                } elseif (isset($response['body']) === true) {
                    $response['body'] = stream_get_contents($response['body']);
                    $response['body'] = $this->serializer->deserialize($response['body'], $response['transfer_stats']);
                }

                // @todo : log error
                // @todo : log success
                // @todo : manage 4xx et 5xx status code

                return $response['body'];
            });

            return $response;
        };

        return $handler;
    }

    /**
     * Process error to raised an more comprehensive exception.
     *
     * @param array $request  Request.
     * @param array $response Response.
     *
     * @return ConnectionException
     */
    protected function getConnectionErrorException($request, $response)
    {
        $exception = null;
        $message   = $response['error']->getMessage();
        $exception = new ConnectionException($message);
        if (isset($response['curl'])) {
            switch ($response['curl']['errno']) {
                case CURLE_COULDNT_RESOLVE_HOST:
                    $exception = new CouldNotResolveHostException($message, null, $response['error']);
                    break;
                case CURLE_COULDNT_CONNECT:
                    $exception = new CouldNotConnectToHostException($message, null, $response['error']);
                    break;
                case CURLE_OPERATION_TIMEOUTED:
                    $exception = new OperationTimeoutException($message, null, $response['error']);
                    break;
            }
        }

        return $exception;
    }
}
