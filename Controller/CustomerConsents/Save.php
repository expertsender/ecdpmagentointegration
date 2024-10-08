<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Controller\CustomerConsents;

use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use ExpertSender\Ecdp\Helper\ConsentFormElementHelper;
use ExpertSender\Ecdp\Model\Consent\FormElement\Form;
use ExpertSender\Ecdp\Model\FormsConfig;
use ExpertSender\Ecdp\Service\TaskService;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Save extends Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \ExpertSender\Ecdp\Service\TaskService
     */
    protected $taskService;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;

    /**
     * @var \ExpertSender\Ecdp\Helper\ConsentFormElementHelper
     */
    protected $formElementHelper;

    /**
     * @var \ExpertSender\Ecdp\Model\FormsConfig
     */
    protected $formsConfig;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Psr\Log\LoggerInterface $logger
     * @param \ExpertSender\Ecdp\Service\TaskService $taskService
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \ExpertSender\Ecdp\Helper\ConsentFormElementHelper $formElementHelper
     * @param \ExpertSender\Ecdp\Model\FormsConfig $formsConfig
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        LoggerInterface $logger,
        TaskService $taskService,
        Validator $formKeyValidator,
        ConsentFormElementHelper $formElementHelper,
        FormsConfig $formsConfig
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->logger = $logger;
        $this->taskService = $taskService;
        $this->formKeyValidator = $formKeyValidator;
        $this->formElementHelper = $formElementHelper;
        $this->formsConfig = $formsConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $validFormKey = $this->formKeyValidator->validate($this->getRequest());

        if ($validFormKey && $this->getRequest()->isPost()) {
            $consents = [];
            $data = $this->getRequest()->getPostValue();
            $formType = $this->formsConfig->getProfileEditFormType();
            $messageId = null;

            if (FormsConfig::FORM_TYPE_DOUBLE_OPT_IN === $formType) {
                $messageId = $this->formsConfig->getProfileEditFormMessageId();
            }

            if (isset($data[ConsentFormElementInterface::INPUT_NAME])) {
                foreach ($data[ConsentFormElementInterface::INPUT_NAME] as $consent) {
                    foreach (explode(',', $consent) as $consentId) {
                        $consents[] = (int) $consentId;
                    }
                }
            }

            $consentsData = [];
            $formElements = $this->formElementHelper->getFormElements(Form::PROFILE_EDIT_FORM)->getItems();

            foreach ($formElements as $formElement) {
                foreach ($formElement->getConsentIds() as $consentId) {
                    $consentsData[] = [
                        'id' => $consentId,
                        'value' => in_array($consentId, $consents)
                    ];
                }
            }

            $customData = $this->taskService->addConsentsCustomData([], $consentsData, $formType, $messageId);
            $customerId = $this->customerSession->getCustomerId();
            if ($customerId) {
                try {
                    $this->taskService->createCustomerUpdateTask($customerId, $customData);
                    $this->messageManager->addSuccessMessage(__('Consents have been saved.'));
                } catch (LocalizedException $ex) {
                    $this->messageManager->addErrorMessage(__('Something went wrong while saving consents.'));
                    $this->logger->error($ex->getMessage());
                }
            }
        }
        
        return $resultRedirect->setPath('*/*/manage');
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->customerSession->authenticate()) {
            $this->_actionFlag->set('', 'no-dispatch', true);
        }
        return parent::dispatch($request);
    }
}
