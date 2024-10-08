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
use Magento\Ui\Component\Listing\Columns\Column;

class EcdpOrderStatus extends Column
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
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        EcdpOrderStatus $ecdpOrderStatus,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->ecdpOrderStatus = $ecdpOrderStatus;
        $this->prepareOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($this->options[$item[OrderStatusMappingInterface::ECDP_ORDER_STATUS]])) {
                    $item[OrderStatusMappingInterface::ECDP_ORDER_STATUS] =
                        $this->options[$item[OrderStatusMappingInterface::ECDP_ORDER_STATUS]];
                }
            }
        }

        return $dataSource;
    }

    /**
     * @return void
     */
    protected function prepareOptions()
    {
        $options = [];

        foreach ($this->ecdpOrderStatus->toOptionArray() as $option) {
            $options[$option['value']] = $option['label'];
        }

        $this->options = $options;
    }
}
