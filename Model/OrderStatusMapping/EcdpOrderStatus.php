<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\OrderStatusMapping;

class EcdpOrderStatus
{
    public const PLACED = 'placed';
    public const PAID = 'paid';
    public const COMPLETED = 'completed';
    public const CANCELED = 'canceled';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'placed',
                'label' => __('Placed')
            ],
            [
                'value' => 'paid',
                'label' => __('Paid')
            ],
            [
                'value' => 'completed',
                'label' => __('Completed')
            ],
            [
                'value' => 'canceled',
                'label' => __('Canceled')
            ]
        ];
    }
}
