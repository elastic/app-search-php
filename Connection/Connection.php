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
use Swiftype\AppSearch\Exception\ConnectionException;
use Swiftype\AppSearch\Exception\CouldNotConnectToHostException;
use Swiftype\AppSearch\Exception\CouldNotResolveHostException;
use Swiftype\AppSearch\Exception\OperationTimeoutException;
use Swiftype\AppSearch\Serializer\SerializerInterface;

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
     * @param callable            $handler     Guzzle handler used to issue request.
     * @param SerializerInterface $serializer  JSON serializer.
     * @param LoggerInterface     $logger      Logger used for warning & error.
     * @param LoggerInterface     $tracer      Logger used for tracing.
     */
    public function __construct(
        callable $handler,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        LoggerInterface $tracer
    ) {
        $this->handler    = $handler;
        $this->serializer = $serializer;
        $this->logger     = $logger;
        $this->tracer     = $tracer;
    }

    /**
     * Run the HTTP request and process the result to be usable by the client.
     *
     * @param string     $method  HTTP method (eg. GET, POST, ...).
     * @param string     $uri     URI of the request.
     * @param array|null $params  Query params.
     * @param array|null $body    Request body.
     *
     * @return array
     */
    public function performRequest($method, $uri, $params = null, $body = null)
    {
        $request = [
            'http_method'  => $method,
            'uri'          => $uri,
            'body'         => $body,
            'query_params' => $params,
        ];

        $handler = $this->wrapHandler($this->handler);

        return $handler(array_filter($request));
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
                    $headers = $response['transfer_stats'] ?? [];
                    $response['body'] = $this->serializer->deserialize($response['body'], $headers);
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
