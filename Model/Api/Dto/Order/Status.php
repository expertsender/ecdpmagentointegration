<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Api\Dto\Order;

class Status
{
    /**
     * @var string
     */
    protected $orderId;

    /**
     * @var string
     */
    protected $orderStatus;

    /**
     * @var int|null
     */
    protected $websiteId;

    /**
     * @var int
     */
    protected $magentoStoreId;

    /**
     * @param string $orderId
     * @param string $orderStatus
     */
    public function __construct(
        string $orderId,
        string $orderStatus,
        int $magentoStoreId,
        ?int $websiteId = null
    ) {
        $this->orderId = $orderId;
        $this->orderStatus = $orderStatus;
        $this->magentoStoreId = $magentoStoreId;
        $this->websiteId = $websiteId;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     * @return self
     */
    public function setOrderId(string $orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * @param string $orderStatus
     * @return self
     */
    public function setOrderStatus(string $orderStatus)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWebsiteId()
    {
        return $this->websiteId;
    }

    /**
     * @param int|null $websiteId
     * @return self
     */
    public function setWebsiteId(?int $websiteId)
    {
        $this->websiteId = $websiteId;

        return $this;
    }

    /**
     * @return int
     */
    public function getMagentoStoreId()
    {
        return $this->magentoStoreId;
    }

    /**
     * @param int $magentoStoreId
     * @return self
     */
    public function setMagentoStoreId(int $magentoStoreId)
    {
        $this->magentoStoreId = $magentoStoreId;

        return $this;
    }
}
