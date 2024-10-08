<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class FormType implements ArrayInterface
{
    public const OPTION_SINGLE_OPT_IN = 'single-opt-in';
    public const OPTION_DOUBLE_OPT_IN = 'double-opt-in';

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::OPTION_SINGLE_OPT_IN,
                'label' => __('Single Opt-In')
            ],
            [
                'value' => self::OPTION_DOUBLE_OPT_IN,
                'label' => __('Double Opt-In')
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            self::OPTION_SINGLE_OPT_IN => __('Single Opt-In'),
            self::OPTION_DOUBLE_OPT_IN => __('Double Opt-In')
        ];
    }
}
