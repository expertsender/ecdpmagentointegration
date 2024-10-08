<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class OrderIdentifier implements ArrayInterface
{
    public const OPTION_ID = 'id';
    public const OPTION_SKU = 'sku';

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::OPTION_ID,
                'label' => __('ID')
            ],
            [
                'value' => self::OPTION_SKU,
                'label' => __('SKU')
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            self::OPTION_ID => __('ID'),
            self::OPTION_SKU => __('SKU')
        ];
    }
}
