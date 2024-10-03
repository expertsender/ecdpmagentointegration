<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\FieldMapping;

class Entity
{
    public const CUSTOMER_ENTITY = 1;
    public const PRODUCT_ENTITY = 2;
    public const ORDER_ENTITY = 3;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::CUSTOMER_ENTITY,
                'label' => self::getLabel(self::CUSTOMER_ENTITY)
            ],
            [
                'value' => self::PRODUCT_ENTITY,
                'label' => self::getLabel(self::PRODUCT_ENTITY)
            ],
            [
                'value' => self::ORDER_ENTITY,
                'label' => self::getLabel(self::ORDER_ENTITY)
            ],
        ];
    }

    /**
     * @param int $value
     * @return \Magento\Framework\Phrase
     */
    public function getLabel(int $value)
    {
        if (self::CUSTOMER_ENTITY === $value) {
            return __('Customer');
        } elseif (self::PRODUCT_ENTITY === $value) {
            return __('Product');
        } elseif (self::ORDER_ENTITY === $value) {
            return __('Order');
        }

        return '';
    }
}
