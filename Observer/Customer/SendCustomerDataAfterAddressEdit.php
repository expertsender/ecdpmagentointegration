<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Observer\Customer;

use ExpertSender\Ecdp\Observer\AbstractTaskObserver;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;

class SendCustomerDataAfterAddressEdit extends AbstractTaskObserver
{
    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        /**
         * @var \Magento\Customer\Model\Adress
         */
        $customerAddress = $observer->getEvent()->getCustomerAddress();
        $customer = $customerAddress->getCustomer();

        try {
            if ($customer) {
                $defaultBillingAddress = $customer->getDefaultBillingAddress();

                if (true === $customerAddress->getDefaultBilling() || !$defaultBillingAddress
                    || (int) $defaultBillingAddress->getId() === (int) $customerAddress->getId()
                ) {
                    $this->taskService->createCustomerUpdateTask($customer->getId());
                }
            }
        } catch (LocalizedException $ex) {
            $this->logger->error(
                'There was an error while creating customer synchronisation task: ' . $ex->getMessage(),
                ['customerId' => $customer->getId()]
            );
        }
    }
}
