<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Endpoint;

use Swiftype\AppSearch\Exception\UnexpectedValueException;

/**
 * Abstract endpoint implementation.
 *
 * @package Swiftype\AppSearch\Endpoint
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
     * @var array|null
     */
    protected $routeParams = [];

    /**
     * @var array|null
     */
    protected $paramWhitelist = [];

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
        $uri = $this->uri;

        foreach ($this->routeParams ?? [] as $paramName) {
            $uri = str_replace(sprintf('{%s}', $paramName), $this->params[$paramName], $uri);
        }

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        $params = [];

        foreach ($this->params as $paramName => $paramVal) {
            if (in_array($paramName, $this->paramWhitelist)) {
                $params[$paramName] = $paramVal;
            }
        }

        return $this->processParams($params);
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
        $this->checkParams($params);
        $this->params = $params;

        return $this;
    }

    private function checkParams($params)
    {
        if ($params == null) {
            return;
        }

        $whitelist     = array_merge($this->paramWhitelist, $this->routeParams);
        $invalidParams = array_diff(array_keys($params), $whitelist);
        $countInvalid  = count($invalidParams);

        if ($countInvalid > 0) {
            $whitelist     = implode('", "', $whitelist);
            $invalidParams = implode('", "', $invalidParams);
            $message = '"%s" is not a valid parameter. Allowed parameters are "%s".';
            if ($countInvalid > 1) {
                $message = '"%s" are not valid parameters. Allowed parameters are "%s".';
            }
            throw new UnexpectedValueException(
                sprintf($message, $invalidParams, $whitelist)
            );
        }
    }

    private function processParams($params)
    {
        foreach ($params as $key => $value) {
            $keyPath = explode('.', $key);
            if (count($keyPath) > 1) {
                $suffix   = implode('.', array_slice($keyPath, 1));
                $value    = $this->processParams([$suffix => $value]);
                $params[$keyPath[0]] =  array_merge($params[$keyPath[0]] ?? [], $value);
                unset($params[$key]);
            }
        }

        return $params;
    }
}
