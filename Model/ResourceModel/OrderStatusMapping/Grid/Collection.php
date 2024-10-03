<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\ResourceModel\OrderStatusMapping\Grid;

use Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterface;
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
