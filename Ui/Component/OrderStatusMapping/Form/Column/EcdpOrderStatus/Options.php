<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Ui\Component\OrderStatusMapping\Form\Column\EcdpOrderStatus;

use Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface;
use Endora\ExpertSenderCdp\Model\OrderStatusMapping\EcdpOrderStatus;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface
     */
    protected $orderStatusMappingRepository;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Endora\ExpertSenderCdp\Model\OrderStatusMapping\EcdpOrderStatus
     */
    protected $ecdpOrderStatus;

    /**
     * @param \Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface $orderStatusMappingRepository
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Endora\ExpertSenderCdp\Model\OrderStatusMapping\EcdpOrderStatus $ecdpOrderStatus
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
