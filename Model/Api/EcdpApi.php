<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\Api;

use Endora\ExpertSenderCdp\Model\Config;
use Endora\ExpertSenderCdp\Model\HttpClient;

abstract class EcdpApi
{
    public const HTTP_SUCCESS = 200;
    public const HTTP_CREATED = 201;

    protected const BASE_URL = 'https://api.ecdp.app/';

    /**
     * @var \Endora\ExpertSenderCdp\Model\HttpClient
     */
    protected $httpClient;

    /**
     * @var \Endora\ExpertSenderCdp\Model\Config
     */
    protected $config;

    public function __construct(HttpClient $httpClient, Config $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    /**
     * @param string $endpoint
     * @return string
     */
    protected function getUrl(string $endpoint)
    {
        return self::BASE_URL . $endpoint;
    }
}
