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
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class ResponseSerializationHandler
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
     * @param callable            $handler    original handler
     * @param SerializerInterface $serializer serialize
     */
    public function __construct(callable $handler, SerializerInterface $serializer)
    {
        $this->handler = $handler;
        $this->serializer = $serializer;
    }

    public function __invoke($request)
    {
        $response = Core::proxy(($this->handler)($request), function ($response) use ($request) {
            if (true === isset($response['body'])) {
                $response['body'] = stream_get_contents($response['body']);
                $headers = $response['transfer_stats'] ?? [];
                $response['body'] = $this->serializer->deserialize($response['body'], $headers);
            }

            return $response;
        });

        return $response;
    }
}
