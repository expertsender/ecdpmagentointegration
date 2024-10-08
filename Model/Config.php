<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config
{
    public const MAIN_PATH = 'endora_expertsender/';
    public const API_KEY = 'general/apikey';
    public const TRACK_TRAFFIC_ENABLED = 'general/track_traffic_enabled';
    public const TRACK_TRAFFIC_SCRIPT = 'general/track_traffic_script';
    public const WEBSITE_ID = 'general/website_id';
    public const ENABLE_API_LOG = 'general/enable_api_log';
    public const ORDER_SYNC_MAX_ATTEMPTS = 'cron/order_sync_max_attempts';
    public const CUSTOMER_SYNC_MAX_ATTEMPTS = 'cron/customer_sync_max_attempts';
    public const PHONE_FROM_ADDRESS = 'customer_data/phone_from_address';
    public const SEND_CUSTOMER_PHONE_ENABLED = 'customer_data/send_customer_phone_enabled';
    public const ORDER_IDENTIFIER = 'order_data/order_identifier';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @param string|null $scope
     * @return string|null
     */
    public function getApiKey(?int $scope = null)
    {
        if (null !== $scope) {
            $scope = $this->storeManager->getStore($scope)->getCode();
        }

        return $this->getConfig(self::API_KEY, $scope);
    }

    /**
     * @param string|null $scope
     * @return bool
     */
    public function getTrackTrafficEnabled(?string $scope = null)
    {
        return (bool) $this->getConfig(self::TRACK_TRAFFIC_ENABLED, $scope);
    }

    /**
     * @param string|null $scope
     * @return string|null
     */
    public function getTrackTrafficScript(?string $scope = null)
    {
        return $this->getConfig(self::TRACK_TRAFFIC_SCRIPT, $scope);
    }

    /**
     * @param string|null $scope
     * @return int|null
     */
    public function getWebsiteId(?string $scope = null)
    {
        $websiteId = $this->getConfig(self::WEBSITE_ID, $scope);

        return null !== $websiteId ? (int) $websiteId : $websiteId;
    }

    /**
     * @return bool
     */
    public function isApiLogEnabled()
    {
        return (bool) $this->getConfig(self::ENABLE_API_LOG);
    }

    /**
     * @param string|null $scope
     * @return string|null
     */
    public function getOrderSyncMaxAttempts(?string $scope = null)
    {
        $maxAttempts = $this->getConfig(self::ORDER_SYNC_MAX_ATTEMPTS, $scope);

        return null !== $maxAttempts ? (int) $maxAttempts : $maxAttempts;
    }

    /**
     * @param string|null $scope
     * @return string|null
     */
    public function getCustomerSyncMaxAttempts(?string $scope = null)
    {
        $maxAttempts = $this->getConfig(self::CUSTOMER_SYNC_MAX_ATTEMPTS, $scope);

        return null !== $maxAttempts ? (int) $maxAttempts : $maxAttempts;
    }

    /**
     * @param string|null $scope
     * @return string
     */
    public function getPhoneFromAddress(?string $scope = null)
    {
        return $this->getConfig(self::PHONE_FROM_ADDRESS, $scope);
    }

    /**
     * @param string|null $scope
     * @return bool
     */
    public function isSendCustomerPhoneEnabled(?string $scope = null)
    {
        return (bool) $this->getConfig(self::SEND_CUSTOMER_PHONE_ENABLED, $scope);
    }

    /**
     * @param string|null $scope
     * @return string
     */
    public function getOrderIdentifier(?string $scope = null)
    {
        return $this->getConfig(self::ORDER_IDENTIFIER, $scope);
    }

    /**
     * @param string $key
     * @param string|null $scope
     * @return mixed
     */
    protected function getConfig(string $key, ?string $scope = null)
    {
        return $this->scopeConfig->getValue(
            self::MAIN_PATH . $key,
            ScopeInterface::SCOPE_STORE,
            $scope
        );
    }
}
