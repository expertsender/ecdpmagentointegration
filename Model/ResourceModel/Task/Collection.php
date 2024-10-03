<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\ResourceModel\Task;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'endora_expertsender_task_collection';
    protected $_eventObject = 'task_collection';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(
            \Endora\ExpertSenderCdp\Model\Task::class,
            \Endora\ExpertSenderCdp\Model\ResourceModel\Task::class
        );
    }
}
