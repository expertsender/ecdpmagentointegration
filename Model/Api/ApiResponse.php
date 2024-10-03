<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\Api;

class ApiResponse
{
    /**
     * @var int
     */
    protected $responseCode;

    /**
     * @var string
     */
    protected $data;

    /**
     * @param int $responseCode
     * @param string $data
     */
    public function __construct(int $responseCode, string $data)
    {
        $this->responseCode = $responseCode;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param int $responseCode
     * @return self
     */
    public function setResponseCode(int $responseCode)
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return self
     */
    public function setData(string $data)
    {
        $this->data = $data;

        return $this;
    }
}
