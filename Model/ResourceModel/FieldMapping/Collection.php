<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\ResourceModel\FieldMapping;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'endora_expertsender_field_mapping_collection';
    protected $_eventObject = 'field_mapping_collection';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(
            \Endora\ExpertSenderCdp\Model\FieldMapping::class,
            \Endora\ExpertSenderCdp\Model\ResourceModel\FieldMapping::class
        );
    }
}
