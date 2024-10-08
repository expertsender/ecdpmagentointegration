<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Api;

use Magento\Framework\Api\SearchResultsInterface;

interface FieldMappingSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \ExpertSender\Ecdp\Api\Data\FieldMappingInterface[]
     */
    public function getItems();

    /**
     * @param \ExpertSender\Ecdp\Api\Data\FieldMappingInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
