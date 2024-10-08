<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\ResourceModel\OrderStatusMapping\Grid;

use ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    /**
     * {@inheritdoc}
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if (OrderStatusMappingInterface::MAGENTO_ORDER_STATUSES === $field && isset($condition['in'])) {
            $conditions = [];
            foreach ($condition['in'] as $magentoStatus) {
                $conditions[] = ['finset' => $magentoStatus];
            }

            return parent::addFieldToFilter($field, $conditions);
        }

        return parent::addFieldToFilter($field, $condition);
    }
}
