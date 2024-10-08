<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Plugin;

use ExpertSender\Ecdp\Model\FormsConfig;
use ExpertSender\Ecdp\Service\TaskService;
use Magento\Checkout\Model\PaymentInformationManagement;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Psr\Log\LoggerInterface;

class SaveCustomerConsentsFromPayment
{
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \ExpertSender\Ecdp\Service\TaskService
     */
    protected $taskService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \ExpertSender\Ecdp\Model\FormsConfig
     */
    protected $formsConfig;

    /**
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepositoy
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \ExpertSender\Ecdp\Service\TaskService $taskService
     * @param \Psr\Log\LoggerInterface $logger
     * @param \ExpertSender\Ecdp\Model\FormsConfig
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        CustomerRepositoryInterface $customerRepository,
        TaskService $taskService,
        LoggerInterface $logger,
        FormsConfig $formsConfig
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->customerRepository = $customerRepository;
        $this->taskService = $taskService;
        $this->logger = $logger;
        $this->formsConfig = $formsConfig;
    }

    /**
     * @param \Magento\Checkout\Model\PaymentInformationManagement $subject
     * @param string $cartId
     * @param \Magento\Quote\Api\Data\PaymentInterface $paymentMethod
     * @param \Magento\Quote\Api\Data\AddressInterface $billingAddress
     * @return null
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        PaymentInformationManagement $subject,
        $cartId,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ) {
        $quote = $this->quoteRepository->getActive($cartId);
        $customer = $quote->getCustomer();
        $consents = $paymentMethod->getExtensionAttributes()->getCustomerConsents();
        $formType = $this->formsConfig->getCheckoutFormType();
        $messageId = null;

        if (FormsConfig::FORM_TYPE_DOUBLE_OPT_IN === $formType) {
            $messageId = $this->formsConfig->getCheckoutFormMessageId();
        }

        $customData = $this->taskService->addConsentsCustomData([], $consents, $formType, $messageId);

        try {
            $this->taskService->createCustomerUpdateTask($customer->getId(), $customData);
        } catch (LocalizedException $ex) {
            $this->logger->error(
                'There was an error while creating customer synchronisation task: ' . $ex->getMessage(),
                ['customerId' => $customer->getId()]
            );
        }

        return null;
    }
}
