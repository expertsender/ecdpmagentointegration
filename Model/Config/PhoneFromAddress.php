<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class PhoneFromAddress implements ArrayInterface
{
    public const OPTION_BILLING = 'billing_address';
    public const OPTION_SHIPPING = 'shipping_address';

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::OPTION_BILLING,
                'label' => __('Billing address')
            ],
            [
                'value' => self::OPTION_SHIPPING,
                'label' => __('Shipping address')
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            self::OPTION_BILLING => __('Billing address'),
            self::OPTION_SHIPPING => __('Shipping address')
        ];
    }
}
