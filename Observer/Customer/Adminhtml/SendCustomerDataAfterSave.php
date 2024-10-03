<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Observer\Customer\Adminhtml;

use Endora\ExpertSenderCdp\Observer\AbstractTaskObserver;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;

class SendCustomerDataAfterSave extends AbstractTaskObserver
{
    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();

        /**
         * @var \Magento\Customer\Model\Customer
         */
        $customer = $event->getCustomer();

        try {
            if (isset($event->getRequest()->getPostValue()['customer']['entity_id'])) {
                $this->taskService->createCustomerUpdateTask($customer->getId(), null, (int) $customer->getStoreId());
            } else {
                $this->taskService->createCustomerAddTask($customer->getId(), null, (int) $customer->getStoreId());
            }
        } catch (LocalizedException $ex) {
            $this->logger->error(
                'There was an error while creating customer synchronisation task: ' . $ex->getMessage(),
                ['customerId' => $customer->getId()]
            );
        }
    }
}
