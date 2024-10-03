<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Controller\Adminhtml\Consent\FormElement;

use Endora\ExpertSenderCdp\Api\ConsentFormElementRepositoryInterface;
use Endora\ExpertSenderCdp\Helper\ControllerHelper;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    public const ADMIN_RESOURCE = 'Endora_ExpertSenderCdp::consent_form_element_edit';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Api\ConsentFormElementRepositoryInterface
     */
    protected $consentFormElementRepository;

    /**
     * @var \Endora\ExpertSenderCdp\Helper\ControllerHelper
     */
    protected $controllerHelper;

    /**
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Endora\ExpertSenderCdp\Api\ConsentFormElementRepositoryInterface $consentFormElementRepository
     * @param \Endora\ExpertSenderCdp\Helper\ControllerHelper $controllerHelper
     */
    public function __construct(
        PageFactory $pageFactory,
        ConsentFormElementRepositoryInterface $consentFormElementRepository,
        ControllerHelper $controllerHelper,
        Context $context
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->consentFormElementRepository = $consentFormElementRepository;
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
                $this->consentFormElementRepository->get($id);
            } catch (NoSuchEntityException $ex) {
                $this->messageManager->addErrorMessage(__('Consent Form Element no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Page */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Endora_ExpertSenderCdp::consent_form_element');
        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Consent Form Element Edit') : __('New Consent Form Element')
        );

        return $resultPage;
    }
}
