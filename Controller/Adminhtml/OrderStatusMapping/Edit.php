<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Controller\Adminhtml\OrderStatusMapping;

use Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface;
use Endora\ExpertSenderCdp\Helper\ControllerHelper;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    public const ADMIN_RESOURCE = 'Endora_ExpertSenderCdp::order_status_mapping_edit';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface
     */
    protected $orderStatusMappingRepository;

    /**
     * @var \Endora\ExpertSenderCdp\Helper\ControllerHelper
     */
    protected $controllerHelper;

    /**
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface $orderStatusMappingRepository
     * @param \Endora\ExpertSenderCdp\Helper\ControllerHelper $controllerHelper
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        PageFactory $pageFactory,
        OrderStatusMappingRepositoryInterface $orderStatusMappingRepository,
        ControllerHelper $controllerHelper,
        Context $context
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->orderStatusMappingRepository = $orderStatusMappingRepository;
        $this->controllerHelper = $controllerHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $validationResult = $this->controllerHelper->validateScopedRequest($this->getRequest());

        if (true !== $validationResult) {
            return $validationResult;
        }

        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $this->orderStatusMappingRepository->get($id);
            } catch (NoSuchEntityException $ex) {
                $this->messageManager->addErrorMessage(__('Order Status Mapping no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Page */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Endora_ExpertSenderCdp::order_status_mapping');
        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Order Status Mapping Edit') : __('New Order Status Mapping')
        );

        return $resultPage;
    }
}
