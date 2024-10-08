<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Service;

use ExpertSender\Ecdp\Exception\EESException;
use ExpertSender\Ecdp\Model\Api\OrderApi;
use ExpertSender\Ecdp\Service\DataConverter;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderService
{
    /**
     * @var \ExpertSender\Ecdp\Model\Api\OrderApi
     */
    protected $orderApi;

    /**
     * @var \ExpertSender\Ecdp\Service\DataConverter
     */
    protected $dataConverter;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @param \ExpertSender\Ecdp\Model\Api\OrderApi $orderApi
     * @param \ExpertSender\Ecdp\Service\DataConverter $dataConverter
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
     * @throws \ExpertSender\Ecdp\Exception\EESException
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
     * @throws \ExpertSender\Ecdp\Exception\EESException
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
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Order
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
