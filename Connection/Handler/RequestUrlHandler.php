<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Connection\Handler;

use GuzzleHttp\Ring\Core;

/**
 * This handler add automatically all URIs data to the request.
 *
 * @package Swiftype\AppSearch\Connection\Handler
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class RequestUrlHandler
{
    /**
     * @var string
     */
    private const URI_PREFIX = '/api/as/v1/';

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $scheme;

    /**
     * Constructor.
     *
     * @param callable $handler     original handler
     * @param string   $apiEndpoint API endpoint (eg. http://myserver/).
     */
    public function __construct(callable $handler, $apiEndpoint)
    {
        $this->handler = $handler;

        $urlComponents = parse_url($apiEndpoint);

        $this->scheme = $urlComponents['scheme'];
        $this->host = $urlComponents['host'];

        if (isset($urlComponents['port'])) {
            $this->host = sprintf('%s:%s', $this->host, $urlComponents['port']);
        }
    }

    /**
     * Add host, scheme and uri prefix to the request before calling the original handler.
     *
     * @param array $request original request
     *
     * @return array
     */
    public function __invoke($request)
    {
        $request = Core::setHeader($request, 'host', [$this->host]);
        $request['scheme'] = $this->scheme;
        $request['uri'] = $this->addURIPrefix($request['uri']);

        return ($this->handler)($request);
    }

    /**
     * Add prefix for the URI.
     *
     * @param string $uri
     *
     * @return string
     */
    private function addURIPrefix($uri)
    {
        return sprintf('%s%s', '/' == substr($uri, 0, 1) ? rtrim(self::URI_PREFIX, '/') : self::URI_PREFIX, $uri);
    }
}
