<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Controller\CustomerConsents;

use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use ExpertSender\Ecdp\Model\FormsConfig;
use ExpertSender\Ecdp\Service\TaskService;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class Subscribe extends Action
{
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \ExpertSender\Ecdp\Service\TaskService
     */
    protected $taskService;

    /**
     * @var \ExpertSender\Ecdp\Model\FormsConfig
     */
    protected $formsConfig;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Psr\Log\LoggerInterface $logger
     * @param \ExpertSender\Ecdp\Service\TaskService $taskService
     * @param \ExpertSender\Ecdp\Model\FormsConfig $formsConfig
     */
    public function __construct(
        Context $context,
        CustomerRepositoryInterface $customerRepository,
        LoggerInterface $logger,
        TaskService $taskService,
        FormsConfig $formsConfig
    ) {
        parent::__construct($context);
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
        $this->taskService = $taskService;
        $this->formsConfig = $formsConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPostValue();
            $consents = $data[ConsentFormElementInterface::INPUT_NAME];
            $email = $data['email'];
            $formType = $this->formsConfig->getNewsletterFormType();
            $messageId = null;

            if (FormsConfig::FORM_TYPE_DOUBLE_OPT_IN === $formType) {
                $messageId = $this->formsConfig->getNewsletterFormMessageId();
            }

            $customData = [];
            $customData = $this->taskService->addConsentsCustomData($customData, $consents, $formType, $messageId);

            try {
                $customer = $this->customerRepository->get($email);
                $this->taskService->createCustomerUpdateTask($customer->getId(), $customData);
                $this->messageManager->addSuccessMessage(__('Your subscription has been saved.'));
            } catch (NoSuchEntityException $ex) {
                $customData['email'] = $email;
                $this->taskService->createGuestCustomerUpdateTask($customData);
                $this->messageManager->addSuccessMessage(__('Your subscription has been saved.'));
            } catch (LocalizedException $ex) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving consents.'));
                $this->logger->error($ex->getMessage());
            }
        }
        
        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
