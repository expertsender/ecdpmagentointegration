<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Controller\Adminhtml\FieldMapping;

use ExpertSender\Ecdp\Api\FieldMappingRepositoryInterface;
use ExpertSender\Ecdp\Helper\ControllerHelper;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    public const ADMIN_RESOURCE = 'ExpertSender_Ecdp::field_mapping_edit';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @var \ExpertSender\Ecdp\Api\FieldMappingRepositoryInterface
     */
    protected $fieldMappingRepository;

    /**
     * @var \ExpertSender\Ecdp\Helper\ControllerHelper
     */
    protected $controllerHelper;

    /**
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \ExpertSender\Ecdp\Api\FieldMappingRepositoryInterface $fieldMappingRepository
     * @param \ExpertSender\Ecdp\Helper\ControllerHelper $controllerHelper
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        PageFactory $pageFactory,
        FieldMappingRepositoryInterface $fieldMappingRepository,
        ControllerHelper $controllerHelper,
        Context $context
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->fieldMappingRepository = $fieldMappingRepository;
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
                $this->fieldMappingRepository->get($id);
            } catch (NoSuchEntityException $ex) {
                $this->messageManager->addErrorMessage(__('Attribute mapping no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Page */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('ExpertSender_Ecdp::field_mapping');
        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Edit Attribute Mapping') : __('New Attribute Mapping')
        );

        return $resultPage;
    }
}
