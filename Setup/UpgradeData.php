<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Setup;

use Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterfaceFactory;
use Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface;
use Endora\ExpertSenderCdp\Model\OrderStatusMapping\EcdpOrderStatus;
use Endora\ExpertSenderCdp\Model\ResourceModel\OrderStatusMapping;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Sales\Model\ResourceModel\Order\Status\Collection;
use Magento\Store\Model\StoreManagerInterface;

class UpgradeData implements UpgradeDataInterface
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
     * @var \Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface
     */
    protected $orderStatusMappingRepository;

    /**
     * @var \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterfaceFactory
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
     * @param \Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface $orderStatusMappingRepository
     * @param \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterfaceFactory $orderStatusMappingFactory
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
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            if ($installer->tableExists(OrderStatusMapping::TABLE_NAME)) {
                $this->createDefaultOrderStatusMappings();
            }
        }

        $installer->endSetup();
    }

    /**
     * @return void
     */
    private function createDefaultOrderStatusMappings()
    {
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
}