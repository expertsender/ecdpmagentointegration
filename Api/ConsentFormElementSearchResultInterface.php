<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Api;

use Magento\Framework\Api\SearchResultsInterface;

interface ConsentFormElementSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface[]
     */
    public function getItems();

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
