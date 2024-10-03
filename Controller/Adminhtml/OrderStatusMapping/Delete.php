<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Controller\Adminhtml\OrderStatusMapping;

use Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    public const ADMIN_RESOURCE = 'Endora_ExpertSenderCdp::order_status_mapping_delete';

    /**
     * @var \Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface
     */
    protected $orderStatusMappingRepository;

    /**
     * @param \Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface $orderStatusMappingRepository
     */
    public function __construct(
        OrderStatusMappingRepositoryInterface $orderStatusMappingRepository,
        Context $context
    ) {
        parent::__construct($context);
        $this->orderStatusMappingRepository = $orderStatusMappingRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create()->setPath('*/*/');
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $this->orderStatusMappingRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('Order Status Mapping has been deleted.'));
            } catch (\Exception $ex) {
                $this->messageManager->addErrorMessage($ex->getMessage());
            }

            return $resultRedirect;
        }

        $this->messageManager->addErrorMessage(__('Order Status Mapping no longer exists.'));
        return $resultRedirect;
    }
}
