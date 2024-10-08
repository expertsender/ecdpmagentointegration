<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Api\Dto;

class Order
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $date;

    /**
     * @var float
     */
    protected $totalValue;

    /**
     * @var \ExpertSender\Ecdp\Model\Api\Dto\Customer
     */
    protected $customer;

    /**
     * @var \ExpertSender\Ecdp\Model\Api\Dto\Product[]
     */
    protected $products;

    /**
     * @var string
     */
    protected $timeZone;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var float
     */
    protected $returnsValue;

    /**
     * @var array
     */
    protected $orderAttributes;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var int|null
     */
    protected $websiteId;

    /**
     * @var int
     */
    protected $magentoStoreId;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date
     * @return self
     */
    public function setDate(string $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalValue()
    {
        return $this->totalValue;
    }

    /**
     * @param float $totalValue
     * @return self
     */
    public function setTotalValue(float $totalValue)
    {
        $this->totalValue = $totalValue;

        return $this;
    }

    /**
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param \ExpertSender\Ecdp\Model\Api\Dto\Customer $customer
     * @return self
     */
    public function setCustomer(\ExpertSender\Ecdp\Model\Api\Dto\Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param \ExpertSender\Ecdp\Model\Api\Dto\Product[] $products
     * @return self
     */
    public function setProducts(array $products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * @param string $timeZone
     * @return self
     */
    public function setTimeZone(string $timeZone)
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return self
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return float
     */
    public function getReturnsValue()
    {
        return $this->returnsValue;
    }

    /**
     * @param float $returnsValue
     * @return self
     */
    public function setReturnsValue(float $returnsValue)
    {
        $this->returnsValue = $returnsValue;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrderAttributes()
    {
        return $this->orderAttributes;
    }

    /**
     * @param array $orderAttributes
     * @return self
     */
    public function setOrderAttributes(array $orderAttributes)
    {
        $this->orderAttributes = $orderAttributes;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return self
     */
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWebsiteId()
    {
        return $this->websiteId;
    }

    /**
     * @param int|null $websiteId
     * @return self
     */
    public function setWebsiteId(?int $websiteId)
    {
        $this->websiteId = $websiteId;

        return $this;
    }

    /**
     * @return int
     */
    public function getMagentoStoreId()
    {
        return $this->magentoStoreId;
    }

    /**
     * @param int $magentoStoreId
     * @return self
     */
    public function setMagentoStoreId(int $magentoStoreId)
    {
        $this->magentoStoreId = $magentoStoreId;

        return $this;
    }
}
