<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Ui\Component\OrderStatusMapping\Listing\Column\MagentoOrderStatuses;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\ResourceModel\Order\Status\Collection;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Status\Collection
     */
    protected $orderStatusCollection;

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\Status\Collection $orderStatusCollection
     */
    public function __construct(Collection $orderStatusCollection)
    {
        $this->orderStatusCollection = $orderStatusCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->orderStatusCollection->toOptionArray();
    }
}
