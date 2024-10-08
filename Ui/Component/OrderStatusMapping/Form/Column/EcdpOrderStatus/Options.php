<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Ui\Component\OrderStatusMapping\Form\Column\EcdpOrderStatus;

use ExpertSender\Ecdp\Api\OrderStatusMappingRepositoryInterface;
use ExpertSender\Ecdp\Model\OrderStatusMapping\EcdpOrderStatus;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \ExpertSender\Ecdp\Api\OrderStatusMappingRepositoryInterface
     */
    protected $orderStatusMappingRepository;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \ExpertSender\Ecdp\Model\OrderStatusMapping\EcdpOrderStatus
     */
    protected $ecdpOrderStatus;

    /**
     * @param \ExpertSender\Ecdp\Api\OrderStatusMappingRepositoryInterface $orderStatusMappingRepository
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \ExpertSender\Ecdp\Model\OrderStatusMapping\EcdpOrderStatus $ecdpOrderStatus
     */
    public function __construct(
        OrderStatusMappingRepositoryInterface $orderStatusMappingRepository,
        RequestInterface $request,
        EcdpOrderStatus $ecdpOrderStatus
    ) {
        $this->orderStatusMappingRepository = $orderStatusMappingRepository;
        $this->request = $request;
        $this->ecdpOrderStatus = $ecdpOrderStatus;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            $options = $this->ecdpOrderStatus->toOptionArray();
            $mappingId = $this->request->getParam('id');

            foreach ($options as $key => $option) {
                $mapping = $this->orderStatusMappingRepository->getByEcdpStatus(
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
