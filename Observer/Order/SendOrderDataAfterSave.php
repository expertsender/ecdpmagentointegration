<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Observer\Order;

use Endora\ExpertSenderCdp\Observer\AbstractTaskObserver;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;

class SendOrderDataAfterSave extends AbstractTaskObserver
{
    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        /**
         * @var \Magento\Sales\Model\Order
         */
        $order = $observer->getEvent()->getOrder();

        try {
            $storeId = (int) $order->getStoreId();
            if ('new' === $order->getState()) {
                $this->taskService->createOrderAddTask($order->getEntityId(), $storeId);
            } else {
                $this->taskService->createOrderUpdateTask($order->getEntityId(), $storeId);
            }

            if ($order->getOrigData('status') !== $order->getStatus()) {
                $this->taskService->createOrderStatusUpdateTask($order->getEntityId(), $storeId);
            }
        } catch (LocalizedException $ex) {
            $this->logger->error(
                'There was an error while creating order synchronisation task: ' . $ex->getMessage(),
                ['orderId' => $order->getEntityId()]
            );
        }
    }
}
