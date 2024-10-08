<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model;

use ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface;
use Magento\Framework\Model\AbstractModel;

class OrderStatusMapping extends AbstractModel implements OrderStatusMappingInterface
{
    protected $_eventPrefix = 'endora_expertsender_order_status_mapping';
    protected $_eventObject = 'order_status_mapping';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\ExpertSender\Ecdp\Model\ResourceModel\OrderStatusMapping::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getEcdpOrderStatus()
    {
        return $this->getData(self::ECDP_ORDER_STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function setEcdpOrderStatus(string $ecdpOrderStatus)
    {
        return $this->setData(self::ECDP_ORDER_STATUS, $ecdpOrderStatus);
    }

    /**
     * {@inheritdoc}
     */
    public function getMagentoOrderStatuses()
    {
        return explode(',', $this->getData(self::MAGENTO_ORDER_STATUSES));
    }

    /**
     * {@inheritdoc}
     */
    public function setMagentoOrderStatuses(array $magentoOrderStatuses)
    {
        return $this->setData(self::MAGENTO_ORDER_STATUSES, implode(',', $magentoOrderStatuses));
    }

    /**
     * {@inheritdoc}
     */
    public function setData($key, $value = null)
    {
        parent::setData($key, $value);

        if (isset($this->_data[self::MAGENTO_ORDER_STATUSES])
            && is_array($this->_data[self::MAGENTO_ORDER_STATUSES])
        ) {
            $this->_data[self::MAGENTO_ORDER_STATUSES] = implode(',', $this->_data[self::MAGENTO_ORDER_STATUSES]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStore()
    {
        return $this->getData(self::STORE);
    }

    /**
     * {@inheritdoc}
     */
    public function setStore(int $store)
    {
        return $this->setData(self::STORE, $store);
    }
}
