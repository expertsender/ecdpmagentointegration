<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Plugin;

use Endora\ExpertSenderCdp\Model\FormsConfig;
use Endora\ExpertSenderCdp\Service\TaskService;
use Magento\Checkout\Model\GuestPaymentInformationManagement;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Psr\Log\LoggerInterface;

class GuestSaveCustomerConsentsFromPayment
{

    /**
     * @var \Endora\ExpertSenderCdp\Service\TaskService
     */
    protected $taskService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Endora\ExpertSenderCdp\Model\FormsConfig
     */
    protected $formsConfig;

    /**
     * @param \Endora\ExpertSenderCdp\Service\TaskService $taskService
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Endora\ExpertSenderCdp\Model\FormsConfig $formsConfig
     */
    public function __construct(
        TaskService $taskService,
        LoggerInterface $logger,
        FormsConfig $formsConfig
    ) {
        $this->taskService = $taskService;
        $this->logger = $logger;
        $this->formsConfig = $formsConfig;
    }

    /**
     * @param Magento\Checkout\Model\GuestPaymentInformationManagement $subject
     * @param string $cartId
     * @param string $email
     * @param \Magento\Quote\Api\Data\PaymentInterface $paymentMethod
     * @param \Magento\Quote\Api\Data\AddressInterface|null $billingAddress
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        GuestPaymentInformationManagement $subject,
        $cartId,
        $email,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ) {
        $consents = $paymentMethod->getExtensionAttributes()->getCustomerConsents();
        $formType = $this->formsConfig->getCheckoutFormType();
        $messageId = null;
        $customData = [
            'email' => $email
        ];

        if (FormsConfig::FORM_TYPE_DOUBLE_OPT_IN === $formType) {
            $messageId = $this->formsConfig->getCheckoutFormMessageId();
        }

        $customData = $this->taskService->addConsentsCustomData($customData, $consents, $formType, $messageId);

        try {
            $this->taskService->createGuestCustomerUpdateTask($customData);
        } catch (LocalizedException $ex) {
            $this->logger->error(
                'There was an error while creating guest customer synchronisation task: ' . $ex->getMessage(),
                ['customData' => $customData]
            );
        }

        return null;
    }
}
