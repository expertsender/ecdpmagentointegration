<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\ResourceModel\Consent;

use Endora\ExpertSenderCdp\Api\Data\ConsentInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'endora_expertsender_consent_collection';
    protected $_eventObject = 'consent_collection';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(
            \Endora\ExpertSenderCdp\Model\Consent::class,
            \Endora\ExpertSenderCdp\Model\ResourceModel\Consent::class
        );
    }

    /**
     * @return array
     */
    public function toOptionHash()
    {
        return $this->_toOptionHash('id', 'name');
    }

    public function getOptionArray(int $store)
    {
        $options = $this->_toOptionArray(
            ConsentInterface::ID,
            ConsentInterface::NAME,
            [ConsentInterface::STORE => ConsentInterface::STORE]
        );

        return array_filter($options, function ($consent) use ($store) {
            return ((int) $consent[ConsentInterface::STORE]) === $store;
        });
    }
}