<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\ResourceModel\FieldMapping;

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
            \ExpertSender\Ecdp\Model\FieldMapping::class,
            \ExpertSender\Ecdp\Model\ResourceModel\FieldMapping::class
        );
    }
}
