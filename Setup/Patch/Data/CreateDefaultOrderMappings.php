<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Setup\Patch\Data;

use ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterfaceFactory;
use ExpertSender\Ecdp\Api\OrderStatusMappingRepositoryInterface;
use ExpertSender\Ecdp\Model\OrderStatusMapping\EcdpOrderStatus;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Sales\Model\ResourceModel\Order\Status\Collection;
use Magento\Store\Model\StoreManagerInterface;

class CreateDefaultOrderMappings implements DataPatchInterface
{
    private const DEFAULT_ECDP_ORDER_STATSUES = [
        EcdpOrderStatus::PLACED => [
            'pending',
            'pending_payment',
            'holded'
        ],
        EcdpOrderStatus::PAID => [
            'processing'
        ],
        EcdpOrderStatus::COMPLETED => [
            'complete'
        ],
        EcdpOrderStatus::CANCELED => [
            'canceled',
            'closed'
        ]
    ];

    /**
     * @var \ExpertSender\Ecdp\Api\OrderStatusMappingRepositoryInterface
     */
    protected $orderStatusMappingRepository;

    /**
     * @var \ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterfaceFactory
     */
    protected $orderStatusMappingFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Status\Collection
     */
    protected $orderStatusCollection;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \ExpertSender\Ecdp\Api\OrderStatusMappingRepositoryInterface $orderStatusMappingRepository
     * @param \ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterfaceFactory $orderStatusMappingFactory
     * @param \Magento\Sales\Model\ResourceModel\Order\Status\Collection $orderStatusCollection
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        OrderStatusMappingRepositoryInterface $orderStatusMappingRepository,
        OrderStatusMappingInterfaceFactory $orderStatusMappingFactory,
        Collection $orderStatusCollection,
        StoreManagerInterface $storeManager
    ) {
        $this->orderStatusMappingRepository = $orderStatusMappingRepository;
        $this->orderStatusMappingFactory = $orderStatusMappingFactory;
        $this->orderStatusCollection = $orderStatusCollection;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $mappings = $this->orderStatusMappingRepository->getAll();

        if ($mappings->getTotalCount() > 0) {
            return;
        }

        $allMagentoStatuses = $this->orderStatusCollection->toOptionArray();

        foreach ($this->storeManager->getStores() as $store) {
            foreach (self::DEFAULT_ECDP_ORDER_STATSUES as $status => $mappedStatuses) {
                $magentoStatuses = [];

                foreach ($allMagentoStatuses as $magentoStatus) {
                    if (in_array($magentoStatus['value'], $mappedStatuses)) {
                        $magentoStatuses[] = $magentoStatus['value'];
                    }
                }

                $orderStatusMapping = $this->orderStatusMappingFactory->create()
                    ->setEcdpOrderStatus($status)
                    ->setMagentoOrderStatuses($magentoStatuses)
                    ->setStore((int) $store->getId());

                $this->orderStatusMappingRepository->save($orderStatusMapping);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
