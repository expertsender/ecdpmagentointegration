<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Api\Data;

interface OrderStatusMappingInterface
{
    public const ID = 'id';
    public const ECDP_ORDER_STATUS = 'ecdp_order_status';
    public const MAGENTO_ORDER_STATUSES = 'magento_order_statuses';
    public const STORE = 'store';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return self
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getEcdpOrderStatus();

    /**
     * @param string $ecdpOrderStatus
     * @return self
     */
    public function setEcdpOrderStatus(string $ecdpOrderStatus);

    /**
     * @return array
     */
    public function getMagentoOrderStatuses();

    /**
     * @param array $magentoOrderStatuses
     * @return self
     */
    public function setMagentoOrderStatuses(array $magentoOrderStatuses);

    /**
     * @return int
     */
    public function getStore();

    /**
     * @param int $store
     * @return self
     */
    public function setStore(int $store);
}
