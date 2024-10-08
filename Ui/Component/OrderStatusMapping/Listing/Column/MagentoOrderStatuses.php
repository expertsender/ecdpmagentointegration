<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Ui\Component\OrderStatusMapping\Listing\Column;

use ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Sales\Model\ResourceModel\Order\Status\Collection;
use Magento\Ui\Component\Listing\Columns\Column;

class MagentoOrderStatuses extends Column
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\Status\Collection $orderStatusCollection
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        Collection $orderStatusCollection,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->options = $orderStatusCollection->toOptionHash();
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $statuses = [];

                foreach (explode(',', $item[OrderStatusMappingInterface::MAGENTO_ORDER_STATUSES]) as $status) {
                    $statuses[] = $this->options[$status];
                }

                $item[OrderStatusMappingInterface::MAGENTO_ORDER_STATUSES] = implode(', ', $statuses);
            }
        }

        return $dataSource;
    }
}
