<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch;

/**
 * Client implementation.
 *
 * @package Swiftype\AppSearch
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class Client
{
    private $endpointBuilder;

    public function __construct()
    {
      $this->endpointBuilder = new Endpoint\Builder();
    }

    public function getEngine($engineName) {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('GetEngine');
        $endpoint->setParams($params);

        return $endpoint;
    }

    public function listEngines($pageCurrent, $pageSize) {
        $params = [
            'page.current' => $pageCurrent,
            'page.size' => $pageSize,
        ];

        $endpoint = ($this->endpointBuilder)('ListEngines');
        $endpoint->setParams($params);

        return $endpoint;
    }

    public function search($engineName, $pageCurrent, $pageSize) {
        $params = [
            'engine_name' => $engineName,
            'page.current' => $pageCurrent,
            'page.size' => $pageSize,
        ];

        $endpoint = ($this->endpointBuilder)('Search');
        $endpoint->setParams($params);

        return $endpoint;
    }
}
