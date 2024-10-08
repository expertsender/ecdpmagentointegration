<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Observer\Customer;

use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use ExpertSender\Ecdp\Model\FormsConfig;
use ExpertSender\Ecdp\Observer\AbstractTaskObserver;
use ExpertSender\Ecdp\Service\TaskService;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class SendCustomerDataAfterRegister extends AbstractTaskObserver
{
    /**
     * @var \ExpertSender\Ecdp\Model\FormsConfig
     */
    protected $formsConfig;

    /**
     * @param \ExpertSender\Ecdp\Model\FormsConfig $formsConfig
     * @param \ExpertSender\Ecdp\Service\TaskService $taskService
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        FormsConfig $formsConfig,
        TaskService $taskService,
        LoggerInterface $logger
    ) {
        parent::__construct($taskService, $logger);
        $this->formsConfig = $formsConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Model\Customer */
        $customer = $observer->getEvent()->getCustomer();

        /** @var \Magento\Customer\Controller\Account\CreatePost */
        $accountController = $observer->getEvent()->getAccountController();

        $postData = $accountController->getRequest()->getPostValue();
        $formType = $this->formsConfig->getRegistrationFormType();
        $messageId = null;

        if (FormsConfig::FORM_TYPE_DOUBLE_OPT_IN === $formType) {
            $messageId = $this->formsConfig->getRegistrationFormMessageId();
        }

        $customData = [];

        if (isset($postData[ConsentFormElementInterface::INPUT_NAME])) {
            $customData = $this->taskService->addConsentsCustomData(
                $customData,
                $postData[ConsentFormElementInterface::INPUT_NAME],
                $formType,
                $messageId
            );
        }

        try {
            $this->taskService->createCustomerUpdateTask($customer->getId(), $customData);
        } catch (LocalizedException $ex) {
            $this->logger->error(
                'There was an error while creating customer synchronisation task: ' . $ex->getMessage(),
                ['customerId' => $customer->getId()]
            );
        }
    }
}
