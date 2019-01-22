<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Endpoint;

/**
 * Abstract endpoint implementation.
 *
 * @package Swiftype\AppSearch
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
abstract class AbstractEndpoint implements EndpointInterface
{
    /**
     * @var  string
     */
    protected $method;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var array
     */
    protected $params = null;

    /**
     * @var null|array
     */
    protected $body = null;

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function getURI()
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }
}
