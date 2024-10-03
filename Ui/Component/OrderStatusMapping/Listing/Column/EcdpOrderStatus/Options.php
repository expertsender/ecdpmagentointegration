<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Ui\Component\OrderStatusMapping\Listing\Column\EcdpOrderStatus;

use Endora\ExpertSenderCdp\Model\OrderStatusMapping\EcdpOrderStatus;
use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Endora\ExpertSenderCdp\Model\OrderStatusMapping\EcdpOrderStatus
     */
    protected $ecdpOrderStatus;

    /**
     * @param \Endora\ExpertSenderCdp\Model\OrderStatusMapping\EcdpOrderStatus $ecdpOrderStatus
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
