<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Controller\Adminhtml\OrderStatusMapping;

use Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface;
use Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterfaceFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    public const ADMIN_RESOURCE = 'Endora_ExpertSenderCdp::order_status_mapping_edit';

    /**
     * @var \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterfaceFactory
     */
    protected $orderStatusMappingFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface
     */
    protected $orderStatusMappingRepository;

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterfaceFactory $orderStatusMappingFactory
     * @param \Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface $orderStatusMappingRepository
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        Context $context,
        OrderStatusMappingInterfaceFactory $orderStatusMappingFactory,
        OrderStatusMappingRepositoryInterface $orderStatusMappingRepository
    ) {
        parent::__construct($context);
        $this->orderStatusMappingFactory = $orderStatusMappingFactory;
        $this->orderStatusMappingRepository = $orderStatusMappingRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('id');

            if ($id) {
                try {
                    $orderStatusMapping = $this->orderStatusMappingRepository->get($id);
                } catch (NoSuchEntityException $ex) {
                    $this->messageManager->addErrorMessage(__('Order Status Mapping no longer exists.'));

                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $orderStatusMapping = $this->orderStatusMappingFactory->create();
            }

            $orderStatusMapping->setData($data);

            try {
                $this->orderStatusMappingRepository->save($orderStatusMapping);
                $this->messageManager->addSuccessMessage(__('Order Status Mapping has been saved.'));

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $ex) {
                $this->messageManager->addErrorMessage($ex->getMessage());
            } catch (\Exception $ex) {
                $this->messageManager->addExceptionMessage(
                    $ex,
                    __('Something went wrong while saving Order Status Mapping.')
                );
            }

            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }

        return $resultRedirect->setPath('*/*/');
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
