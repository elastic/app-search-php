<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Connection\Handler;

use GuzzleHttp\Ring\Core;
use Swiftype\AppSearch\Serializer\SerializerInterface;

/**
 * Automatatic serialization of the request params and body.
 *
 * @package Swiftype\AppSearch\Connection\Handler
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class RequestSerializationHandler
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
     * Constructor.
     *
     * @param callable            $handler    Original handler.
     * @param SerializerInterface $serializer Serialize.
     */
    public function __construct(callable $handler, SerializerInterface $serializer)
    {
        $this->handler    = $handler;
        $this->serializer = $serializer;
    }

    public function __invoke($request)
    {
        $request = Core::setHeader($request, 'Content-Type', ['application/json']);

        $body = $request['body'] ?? [];

        if (isset($request['query_params'])) {
            $body = array_merge($body, $request['query_params']);
            unset($request['query_params']);
        }

        if (!empty($body)) {
            ksort($body);
            $request['body'] = $this->serializer->serialize($body);
        }

        return ($this->handler)($request);
    }
}
