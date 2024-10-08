<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Controller\Adminhtml\Consent;

use ExpertSender\Ecdp\Api\ConsentRepositoryInterface;
use ExpertSender\Ecdp\Helper\ControllerHelper;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    public const ADMIN_RESOURCE = 'ExpertSender_Ecdp::consent_edit';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @var \ExpertSender\Ecdp\Api\ConsentRepositoryInterface
     */
    protected $consentRepository;

    /**
     * @var \ExpertSender\Ecdp\Helper\ControllerHelper
     */
    protected $controllerHelper;

    /**
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \ExpertSender\Ecdp\Api\ConsentRepositoryInterface $consentRepository
     * @param \ExpertSender\Ecdp\Helper\ControllerHelper $controllerHelper
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
        $resultPage->setActiveMenu('ExpertSender_Ecdp::consent');
        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Consent Edit') : __('New Consent')
        );

        return $resultPage;
    }
}
