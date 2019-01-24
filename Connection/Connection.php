<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Connection;

use Psr\Log\LoggerInterface;

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
     * @param callable        $handler Guzzle handler used to issue request.
     * @param LoggerInterface $logger  Logger used for warning & error.
     * @param LoggerInterface $tracer  Logger used for tracing.
     */
    public function __construct(callable $handler, LoggerInterface $logger, LoggerInterface $tracer)
    {
        $this->handler    = $handler;
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

        return ($this->handler)(array_filter($request));
    }
}
