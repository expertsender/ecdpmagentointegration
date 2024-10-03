<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Controller\Adminhtml\OrderStatusMapping;

use Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface;
use Endora\ExpertSenderCdp\Model\ResourceModel\OrderStatusMapping\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    public const ADMIN_RESOURCE = 'Endora_ExpertSenderCdp::order_status_mapping_delete';

    /**
     * @var \Endora\ExpertSenderCdp\Model\ResourceModel\OrderStatusMapping\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface
     */
    protected $orderStatusMappingRepository;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Endora\ExpertSenderCdp\Model\ResourceModel\OrderStatusMapping\CollectionFactory $collectionFactory
     * @param \Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface $orderStatusMappingRepository
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        OrderStatusMappingRepositoryInterface $orderStatusMappingRepository,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->orderStatusMappingRepository = $orderStatusMappingRepository;
        $this->filter = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $deleted = 0;

        foreach ($collection->getItems() as $orderStatusMapping) {
            $this->orderStatusMappingRepository->delete($orderStatusMapping);
            $deleted++;
        }

        if ($deleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) has been deleted.', $deleted)
            );
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE)
            && strtolower($this->getRequest()->getMethod()) === 'post';
    }
}
