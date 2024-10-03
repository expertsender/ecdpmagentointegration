<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Service;

use Endora\ExpertSenderCdp\Exception\EESException;
use Endora\ExpertSenderCdp\Model\Api\OrderApi;
use Endora\ExpertSenderCdp\Service\DataConverter;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderService
{
    /**
     * @var \Endora\ExpertSenderCdp\Model\Api\OrderApi
     */
    protected $orderApi;

    /**
     * @var \Endora\ExpertSenderCdp\Service\DataConverter
     */
    protected $dataConverter;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @param \Endora\ExpertSenderCdp\Model\Api\OrderApi $orderApi
     * @param \Endora\ExpertSenderCdp\Service\DataConverter $dataConverter
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        OrderApi $orderApi,
        DataConverter $dataConverter,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->orderApi = $orderApi;
        $this->dataConverter = $dataConverter;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param int $orderId
     * @return bool
     * @throws \Endora\ExpertSenderCdp\Exception\EESException
     */
    public function sendNewOrder(int $orderId)
    {
        $response = $this->orderApi->addOrder($this->getOrderDto($orderId));

        if (OrderApi::HTTP_CREATED !== $response->getResponseCode()) {
            throw new EESException(__('Order add synchronization error | ' . $response->getData()));
        }

        return true;
    }

    /**
     * @param int $orderId
     * @return bool
     * @throws \Endora\ExpertSenderCdp\Exception\EESException
     */
    public function sendOrderUpdate(int $orderId)
    {
        $response = $this->orderApi->addOrReplaceOrder($this->getOrderDto($orderId));

        if (OrderApi::HTTP_CREATED !== $response->getResponseCode()) {
            throw new EESException(__('Order update synchronization error | ' . $response->getData()));
        }

        return true;
    }

    public function sendOrderStatusUpdate(int $orderId)
    {
        $response = $this->orderApi->updateOrderStatus($this->getOrderStatusDto($orderId));

        if (OrderApi::HTTP_SUCCESS !== $response->getResponseCode()) {
            throw new EESException(__('Order status update synchronization error | ' . $response->getData()));
        }

        return true;
    }

    /**
     * @param int $orderId
     * @return \Endora\ExpertSenderCdp\Model\Api\Dto\Order
     */
    public function getOrderDto(int $orderId)
    {
        $order = $this->orderRepository->get($orderId);
        return $this->dataConverter->orderToDto($order);
    }

    public function getOrderStatusDto(int $orderId)
    {
        $order = $this->orderRepository->get($orderId);
        return $this->dataConverter->orderToStatusDto($order);
    }
}
