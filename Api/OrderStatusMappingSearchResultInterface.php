<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Api;

use Magento\Framework\Api\SearchResultsInterface;

interface OrderStatusMappingSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface[]
     */
    public function getItems();

    /**
     * @param \ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
