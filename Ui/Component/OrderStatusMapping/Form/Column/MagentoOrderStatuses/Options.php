<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Ui\Component\OrderStatusMapping\Form\Column\MagentoOrderStatuses;

use ExpertSender\Ecdp\Api\OrderStatusMappingRepositoryInterface;
use Magento\Framework\App\RequestInterface;
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
     * @var \ExpertSender\Ecdp\Api\OrderStatusMappingRepositoryInterface
     */
    protected $orderStatusMappingRepository;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\Status\Collection $orderStatusCollection
     * @param \ExpertSender\Ecdp\Api\OrderStatusMappingRepositoryInterface $orderStatusMappingRepository
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        Collection $orderStatusCollection,
        OrderStatusMappingRepositoryInterface $orderStatusMappingRepository,
        RequestInterface $request
    ) {
        $this->orderStatusCollection = $orderStatusCollection;
        $this->orderStatusMappingRepository = $orderStatusMappingRepository;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            $options = $this->orderStatusCollection->toOptionArray();
            $mappingId = $this->request->getParam('id');

            foreach ($options as $key => $option) {
                $mapping = $this->orderStatusMappingRepository->getByMagentoStatus(
                    $option['value'],
                    (int) $this->request->getParam('store')
                );

                if (null !== $mapping && $mapping->getId() !== $mappingId) {
                    unset($options[$key]);
                }
            }

            $this->options = $options;
        }

        return $this->options;
    }
}
