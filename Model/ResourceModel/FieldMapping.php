<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class FieldMapping extends AbstractDb
{
    public const TABLE_NAME = 'endora_expertsender_field_mappings';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'id');
    }
}