<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\Api;

class SettingsApi extends EcdpApi
{
    protected const CONSENTS_URL = 'customerconsents';
    protected const CUSTOMER_ATTRIBUTES_URL = 'customerattributes';
    protected const PRODUCT_ATTRIBUTES_URL = 'productattributes';
    protected const ORDER_ATTRIBUTES_URL = 'orderattributes';

    /**
     * @param int $storeId
     * @return \Endora\ExpertSenderCdp\Model\Api\ApiResponse
     */
    public function getCustomerConsents(int $storeId)
    {
        return $this->httpClient->makeGet(
            $this->getUrl(self::CONSENTS_URL),
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return \Endora\ExpertSenderCdp\Model\Api\ApiResponse
     */
    public function getCustomerAttributes(int $storeId)
    {
        return $this->httpClient->makeGet(
            $this->getUrl(self::CUSTOMER_ATTRIBUTES_URL),
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return \Endora\ExpertSenderCdp\Model\Api\ApiResponse
     */
    public function getProductAttributes(int $storeId)
    {
        return $this->httpClient->makeGet(
            $this->getUrl(self::PRODUCT_ATTRIBUTES_URL),
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return \Endora\ExpertSenderCdp\Model\Api\ApiResponse
     */
    public function getOrderAttributes(int $storeId)
    {
        return $this->httpClient->makeGet(
            $this->getUrl(self::ORDER_ATTRIBUTES_URL),
            $storeId
        );
    }
}
