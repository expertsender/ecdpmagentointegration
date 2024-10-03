<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\Api;

use Endora\ExpertSenderCdp\Model\Api\Dto\Order;
use Endora\ExpertSenderCdp\Model\Api\Dto\Order\Status;
use Endora\ExpertSenderCdp\Model\Api\EcdpApi;

class OrderApi extends EcdpApi
{
    protected const URL = 'orders';

    protected const MODE_ADD = 'Add';
    protected const MODE_ADD_OR_REPLACE = 'AddOrReplace';
    protected const MODE_REPLACE = 'Replace';

    /**
     * @param \Endora\ExpertSenderCdp\Model\Api\Dto\Order $order
     * @return \Endora\ExpertSenderCdp\Model\Api\ApiResponse
     */
    public function addOrder(Order $order)
    {
        return $this->postOrder($order, self::MODE_ADD);
    }

    /**
     * @param \Endora\ExpertSenderCdp\Model\Api\Dto\Order $order
     * @return \Endora\ExpertSenderCdp\Model\Api\ApiResponse
     */
    public function addOrReplaceOrder(Order $order)
    {
        return $this->postOrder($order, self::MODE_ADD_OR_REPLACE);
    }

    /**
     * @param \Endora\ExpertSenderCdp\Model\Api\Dto\Order $order
     * @return \Endora\ExpertSenderCdp\Model\Api\ApiResponse
     */
    public function replaceOrder(Order $order)
    {
        return $this->postOrder($order, self::MODE_REPLACE);
    }

    /**
     * @param \Endora\ExpertSenderCdp\Model\Api\Dto\Order\Status $orderStatus
     * @return \Endora\ExpertSenderCdp\Model\Api\ApiResponse
     */
    public function updateOrderStatus(Status $orderStatus)
    {
        $payload = [
            'orderId' => $orderStatus->getOrderId(),
            'orderStatus' => $orderStatus->getOrderStatus()
        ];

        if (null !== $orderStatus->getWebsiteId()) {
            $payload['websiteId'] = $orderStatus->getWebsiteId();
        }

        return $this->httpClient->makePut(
            $this->getUrl(self::URL . '/status'),
            $payload,
            $orderStatus->getMagentoStoreId()
        );
    }

    /**
     * @param \Endora\ExpertSenderCdp\Model\Api\Dto\Order $order
     * @param string $mode
     * @return \Endora\ExpertSenderCdp\Model\Api\ApiResponse
     */
    protected function postOrder(Order $order, string $mode)
    {
        $customer = $order->getCustomer();
        $products = $order->getProducts();

        $productsArray = [];

        foreach ($products as $product) {
            $productsArray[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $product->getQuantity(),
                'returned' => $product->getReturned(),
                'url' => $product->getUrl(),
                'imageUrl' => $product->getImageUrl(),
                'category' => $product->getCategory(),
                'productAttributes' => $product->getProductAttributes()
            ];
        }

        $data = [
            'id' => $order->getId(),
            'date' => $order->getDate(),
            'totalValue' => $order->getTotalValue(),
            'customer' => [
                'email' => $customer->getEmail(),
                'phone' => $customer->getPhone(),
                'crmId' => $customer->getCrmId()
            ],
            'products' => $productsArray,
            'timeZone' => $order->getTimeZone(),
            'websiteId' => $order->getWebsiteId(),
            'currency' => $order->getCurrency(),
            'returnsValue' => $order->getReturnsValue(),
            'orderAttributes' => $order->getOrderAttributes()
        ];

        if (null !== $order->getWebsiteId()) {
            $data['websiteId'] = $order->getWebsiteId();
        }

        $payload = [
            'data' => [$data],
            'mode' => $mode
        ];

        return $this->httpClient->makePost(
            $this->getUrl(self::URL),
            $payload,
            $order->getMagentoStoreId()
        );
    }
}