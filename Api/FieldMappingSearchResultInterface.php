<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Api;

use Magento\Framework\Api\SearchResultsInterface;

interface FieldMappingSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Endora\ExpertSenderCdp\Api\Data\FieldMappingInterface[]
     */
    public function getItems();

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\FieldMappingInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
