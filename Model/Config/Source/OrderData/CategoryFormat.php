<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Config\Source\OrderData;

use Magento\Framework\Data\OptionSourceInterface;

class CategoryFormat implements OptionSourceInterface
{
    public const VALUE_ALL = 'all';
    public const VALUE_LAST = 'last';

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::VALUE_ALL,
                'label' => __('All categories')
            ],
            [
                'value' => self::VALUE_LAST,
                'label' => __('Last category')
            ]
        ];
    }
}