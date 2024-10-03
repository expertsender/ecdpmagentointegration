<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Controller\Adminhtml\Consent;

use Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface;
use Endora\ExpertSenderCdp\Helper\ControllerHelper;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    public const ADMIN_RESOURCE = 'Endora_ExpertSenderCdp::consent_edit';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface
     */
    protected $consentRepository;

    /**
     * @var \Endora\ExpertSenderCdp\Helper\ControllerHelper
     */
    protected $controllerHelper;

    /**
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface $consentRepository
     * @param \Endora\ExpertSenderCdp\Helper\ControllerHelper $controllerHelper
     */
    public function __construct(
        PageFactory $pageFactory,
        ConsentRepositoryInterface $consentRepository,
        ControllerHelper $controllerHelper,
        Context $context
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->consentRepository = $consentRepository;
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
                $this->consentRepository->get($id);
            } catch (NoSuchEntityException $ex) {
                $this->messageManager->addErrorMessage(__('Consent no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Page */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Endora_ExpertSenderCdp::consent');
        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Consent Edit') : __('New Consent')
        );

        return $resultPage;
    }
}
