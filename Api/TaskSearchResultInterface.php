<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Api;

use Magento\Framework\Api\SearchResultsInterface;

interface TaskSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \ExpertSender\Ecdp\Api\Data\TaskInterface[]
     */
    public function getItems();

    /**
     * @param \ExpertSender\Ecdp\Api\Data\TaskInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
