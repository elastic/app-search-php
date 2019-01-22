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

/**
 * Connection bring HTTP connectivity to the Swiftype HTTP API.
 *
 * @package Swiftype\AppSearch
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
     * Constr
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

    public function performRequest($method, $uri, $params = null, $body = null, $options = [])
    {
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

    private function getURI($uri, $params)
    {
        if (!empty($params)) {
            array_walk($params, function (&$value, &$key) {
                if ($value === true) {
                    $value = 'true';
                } else if ($value === false) {
                    $value = 'false';
                }
            });

                $uri .= '?' . http_build_query($params);
        }

        return sprintf("%s/%s", $this->connectionParams['uriPrefix'], $uri);
    }

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

    protected function getConnectionErrorException($request, $response)
    {
        $exception = null;
        $message = $response['error']->getMessage();
        $exception = new \Swiftype\AppSearch\Exception\ConnectionException($message);
        if ( isset($response['curl'])) {
            switch ($response['curl']['errno']) {
                case CURLE_COULDNT_RESOLVE_HOST:
                    $exception = new \Swiftype\AppSearch\Exception\CouldNotResolveHostException($message, null, $response['error']);
                    break;
                case CURLE_COULDNT_CONNECT:
                    $exception = new \Swiftype\AppSearch\Exception\CouldNotConnectToHostException($message, null, $response['error']);
                    break;
                case CURLE_OPERATION_TIMEOUTED:
                    $exception = new \Swiftype\AppSearch\Exception\OperationTimeoutException($message, null, $response['error']);
                    break;
            }
        }

        return $exception;
    }
}
