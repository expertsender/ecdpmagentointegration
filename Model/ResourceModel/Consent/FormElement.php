<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\ResourceModel\Consent;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class FormElement extends AbstractDb
{
    public const TABLE_NAME = 'endora_expertsender_consent_form_elements';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'id');
    }
}
