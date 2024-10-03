<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Api;

use Magento\Framework\Api\SearchResultsInterface;

interface ConsentSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Endora\ExpertSenderCdp\Api\Data\ConsentInterface[]
     */
    public function getItems();

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\ConsentInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
