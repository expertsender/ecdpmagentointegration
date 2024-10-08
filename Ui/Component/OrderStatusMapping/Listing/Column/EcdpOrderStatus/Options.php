<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Ui\Component\OrderStatusMapping\Listing\Column\EcdpOrderStatus;

use ExpertSender\Ecdp\Model\OrderStatusMapping\EcdpOrderStatus;
use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \ExpertSender\Ecdp\Model\OrderStatusMapping\EcdpOrderStatus
     */
    protected $ecdpOrderStatus;

    /**
     * @param \ExpertSender\Ecdp\Model\OrderStatusMapping\EcdpOrderStatus $ecdpOrderStatus
     */
    public function __construct(EcdpOrderStatus $ecdpOrderStatus)
    {
        $this->ecdpOrderStatus = $ecdpOrderStatus;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            $this->options = $this->ecdpOrderStatus->toOptionArray();
        }

        return $this->options;
    }
}
