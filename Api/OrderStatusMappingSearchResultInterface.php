<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Api;

use Magento\Framework\Api\SearchResultsInterface;

interface OrderStatusMappingSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterface[]
     */
    public function getItems();

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
