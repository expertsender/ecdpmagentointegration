<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\ResourceModel\Consent\FormElement;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'endora_expertsender_consent_form_element_collection';
    protected $_eventObject = 'form_element_collection';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(
            \ExpertSender\Ecdp\Model\Consent\FormElement::class,
            \ExpertSender\Ecdp\Model\ResourceModel\Consent\FormElement::class
        );
    }
}
