<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Service;

use ExpertSender\Ecdp\Api\ConsentRepositoryInterface;
use ExpertSender\Ecdp\Api\Data\ConsentInterface;
use ExpertSender\Ecdp\Api\FieldMappingRepositoryInterface;
use ExpertSender\Ecdp\Api\OrderStatusMappingRepositoryInterface;
use ExpertSender\Ecdp\Exception\EESException;
use ExpertSender\Ecdp\Model\Api\Dto\Customer;
use ExpertSender\Ecdp\Model\Api\Dto\Customer\Consent;
use ExpertSender\Ecdp\Model\Api\Dto\Customer\ConsentsData;
use ExpertSender\Ecdp\Model\Api\Dto\Order;
use ExpertSender\Ecdp\Model\Api\Dto\Order\Status;
use ExpertSender\Ecdp\Model\Api\Dto\Product;
use ExpertSender\Ecdp\Model\Config;
use ExpertSender\Ecdp\Model\Config\OrderIdentifier;
use ExpertSender\Ecdp\Model\Config\PhoneFromAddress;
use ExpertSender\Ecdp\Service\Converter\CategoryResolver;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\UrlInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class DataConverter
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * @var \ExpertSender\Ecdp\Api\FieldMappingRepositoryInterface
     */
    protected $fieldMappingRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Api\AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * @var \ExpertSender\Ecdp\Api\ConsentRepositoryInterface
     */
    protected $consentRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \ExpertSender\Ecdp\Model\Config
     */
    protected $config;

    /**
     * @var \ExpertSender\Ecdp\Api\OrderStatusMappingRepositoryInterface
     */
    protected $orderStatusMappingRepository;

    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * @var \ExpertSender\Ecdp\Service\Converter\CategoryResolver
     */
    protected $categoryResolver;

    /**
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \ExpertSender\Ecdp\Api\FieldMappingRepositoryInterface $fieldMappingRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
     * @param \ExpertSender\Ecdp\Api\ConsentRepositoryInterface $consentRepository
     * @param \Psr\Log\LoggerInterface $logger
     * @param \ExpertSender\Ecdp\Model\Config $config
     * @param \ExpertSender\Ecdp\Api\OrderStatusMappingRepositoryInterface $orderStatusMappingRepository
     * @param \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param \ExpertSender\Ecdp\Service\Converter\CategoryResolver $categoryResolver
     */
    public function __construct(
        TimezoneInterface $timezone,
        FieldMappingRepositoryInterface $fieldMappingRepository,
        StoreManagerInterface $storeManager,
        AddressRepositoryInterface $addressRepository,
        ConsentRepositoryInterface $consentRepository,
        LoggerInterface $logger,
        Config $config,
        OrderStatusMappingRepositoryInterface $orderStatusMappingRepository,
        AttributeRepositoryInterface $attributeRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        CategoryResolver $categoryResolver
    ) {
        $this->timezone = $timezone;
        $this->fieldMappingRepository = $fieldMappingRepository;
        $this->storeManager = $storeManager;
        $this->addressRepository = $addressRepository;
        $this->consentRepository = $consentRepository;
        $this->logger = $logger;
        $this->config = $config;
        $this->orderStatusMappingRepository = $orderStatusMappingRepository;
        $this->attributeRepository = $attributeRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->categoryResolver = $categoryResolver;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Order
     */
    public function orderToDto(OrderInterface $order)
    {
        $orderDto = new Order();
        $customerDto = new Customer();
        $mappings = $this->fieldMappingRepository->getOrderFieldMappings(
            (int) $order->getStoreId()
        );
        $address = null;
        $websiteId = $this->config->getWebsiteId($order->getStoreId());

        if (PhoneFromAddress::OPTION_BILLING === $this->config->getPhoneFromAddress() || $order->getIsVirtual()) {
            $address = $order->getBillingAddress();
        } else {
            /** @var \Magento\Sales\Api\Data\OrderAddressInterface */
            $address = $order->getShippingAddress();
        }

        $customerDto->setEmail($order->getCustomerEmail())
            ->setPhone($address->getTelephone())
            ->setCrmId($order->getCustomerId() ?? '0');

        $orderDto->setId($order->getIncrementId())
            ->setDate($this->formatDateTime($order->getUpdatedAt() ?? $order->getCreatedAt()))
            ->setTimeZone($this->timezone->getDefaultTimezone())
            ->setTotalValue($order->getGrandTotal())
            ->setCurrency($order->getBaseCurrencyCode())
            ->setReturnsValue($order->getSubtotalCanceled() ?? 0)
            ->setCustomer($customerDto)
            ->setProducts($this->orderToProductDtoArray($order))
            ->setOrderAttributes($this->getEntityAttributes($order, $mappings->getItems()))
            ->setStatus($this->getOrderStatus($order))
            ->setWebsiteId($websiteId)
            ->setMagentoStoreId((int) $order->getStoreId());

        return $orderDto;
    }

    /**
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Customer
     */
    public function customerToDto(
        CustomerInterface $customer,
        ?array $customData = null
    ) {
        $customerDto = new Customer();
        $phone = null;
        $storeId = $customer->getStoreId();

        if (true === $this->config->isSendCustomerPhoneEnabled($storeId)) {
            $phoneFromAddress = $this->config->getPhoneFromAddress($storeId);
            $defaultAddressId = PhoneFromAddress::OPTION_BILLING === $phoneFromAddress
                ? $customer->getDefaultBilling() : $customer->getDefaultShipping();

            if ($defaultAddressId) {
                $address = $this->addressRepository->getById($defaultAddressId);
                $phone = $address->getTelephone();
            }
        }

        $mappings = $this->fieldMappingRepository->getCustomerFieldMappings((int) $storeId);
        $dateOfBirth = $customer->getDob();
        $gender = $customer->getGender();

        $customerDto->setEmail(strtolower($customer->getEmail()))
            ->setCrmId((int) $customer->getId())
            ->setFirstName($customer->getFirstname())
            ->setLastName($customer->getLastname())
            ->setPhone($phone)
            ->setDateOfBirth(null !== $dateOfBirth ? $this->formatDateTime($dateOfBirth) : null)
            ->setGender(null !== $gender ? (int) $gender : null)
            ->setCustomAttributes($this->getEntityAttributes($customer, $mappings->getItems()))
            ->setConsentsData($this->getConsentsData($customData, (int) $storeId))
            ->setMagentoStoreId((int) $storeId);

        return $customerDto;
    }

    /**
     * @param array $customData
     * @param int $storeId
     */
    public function guestCustomerToDto(array $customData, int $storeId)
    {
        $customerDto = new Customer();
        $customerDto->setEmail($customData['email'])
            ->setConsentsData($this->getConsentsData($customData, $storeId))
            ->setMagentoStoreId($storeId);

        return $customerDto;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Order\Status
     */
    public function orderToStatusDto(OrderInterface $order)
    {
        $websiteId = $this->config->getWebsiteId($order->getStoreId());
        $statusMapping = $this->orderStatusMappingRepository->getByMagentoStatus(
            $order->getStatus(),
            (int) $order->getStoreId()
        );

        if (null === $statusMapping) {
            throw new EESException(__(
                'Error while order status update | No status mapping found for Magento status "%1"',
                $order->getStatus()
            ));
        }

        return new Status(
            $order->getIncrementId(),
            $statusMapping->getEcdpOrderStatus(),
            (int) $order->getStoreId(),
            $websiteId
        );
    }

    /**
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param int $storeId
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Customer\ConsentsData
     */
    protected function getConsentsData(?array $customData, int $storeId)
    {
        $consentsData = new ConsentsData();
        $dtoArray = [];

        if (null !== $customData && isset($customData['consentsData'])) {
            $consentIds = array_keys($customData['consentsData']['consents']);

            $searchCriteria = $this->searchCriteriaBuilderFactory->create()
                ->addFilter(ConsentInterface::ECDP_ID, $consentIds, 'in')
                ->addFilter(ConsentInterface::STORE, $storeId)
                ->create();

            foreach ($this->consentRepository->getList($searchCriteria)->getItems() as $consent) {
                $dtoArray[] = new Consent(
                    $consent->getEcdpId(),
                    $customData['consentsData']['consents'][$consent->getEcdpId()]
                );
            }

            $consentsData->setConsents($dtoArray);

            if (isset($customData['consentsData']['messageId'])) {
                $consentsData->setConfirmationMessageId((int) $customData['consentsData']['messageId']);
            }
        }

        return $consentsData;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return array
     */
    protected function orderToProductDtoArray(OrderInterface $order)
    {
        $productDtoArray = [];
        $mappings = $this->fieldMappingRepository->getProductFieldMappings(
            (int) $order->getStoreId()
        );
        $store = $this->storeManager->getStore($order->getStoreId());

        /** @var \Magento\Sales\Model\Order\Item[] */
        $orderItems = $order->getAllItems();

        foreach ($order->getAllVisibleItems() as $orderItem) {
            /** @var \Magento\Catalog\Model\Product|null */
            $product = $orderItem->getProduct();
            $product->setStoreId($store->getId());

            if ($product) {
                $category = $this->categoryResolver->execute(
                    $product->getCategoryIds(),
                    (int) $store->getId()
                );
                $childProduct = null;

                if (Configurable::TYPE_CODE === $product->getTypeId()) {
                    $parentItemId = $orderItem->getId();

                    /** @var \Magento\Sales\Model\Order\Item $item */
                    $childItems = array_filter($orderItems, function ($item) use ($parentItemId) {
                        return $item->getParentItemId() === $parentItemId;
                    });

                    $childItem = array_shift($childItems);

                    if ($childItem) {
                        $childProduct = $childItem->getProduct();
                        $childProduct->setStoreId($store->getId());
                    }
                }

                $productDto = new Product();
                $imageUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'catalog/product'
                    . (null !== $childProduct ? $childProduct->getImage() : $product->getImage());
                $identifier = OrderIdentifier::OPTION_ID === $this->config->getOrderIdentifier($store->getId()) ?
                    $orderItem->getProductId() : $orderItem->getSku();

                $productDto->setId($identifier)
                    ->setName($product->getName())
                    ->setPrice($orderItem->getPriceInclTax())
                    ->setQuantity($orderItem->getQtyOrdered())
                    ->setReturned($orderItem->getQtyReturned() ?? 0)
                    ->setUrl($product->getProductUrl())
                    ->setImageUrl($imageUrl)
                    ->setCategory($category)
                    ->setProductAttributes($this->getEntityAttributes($product, $mappings->getItems(), $childProduct));

                $productDtoArray[] = $productDto;
            }
        }

        return $productDtoArray;
    }

    /**
     * @param string $date
     * @return string
     */
    protected function formatDateTime(string $date)
    {
        return (new \DateTime($date))->format('Y-m-d\\TH:i:s\Z');
    }

    /**
     * @param OrderInterface|ProductInterface|CustomerInterface $entity
     * @param array $fieldMappings
     * @param ProductInterface|null $childProduct
     * @return array
     */
    protected function getEntityAttributes($entity, array $fieldMappings, ?ProductInterface $childProduct = null)
    {
        $entityAttributes = [];

        foreach ($fieldMappings as $mapping) {
            $value = null;

            if ($entity instanceof ProductInterface) {
                $value = $this->getProductValue($entity, $mapping->getMagentoField(), $childProduct);
            } elseif ($entity instanceof CustomerInterface) {
                $value = $this->getCustomerValue($entity, $mapping->getMagentoField());
            } elseif ($entity instanceof OrderInterface) {
                $value = $this->getOrderValue($entity, $mapping->getMagentoField());
            }

            $entityAttributes[] = [
                'name' => $mapping->getEcdpField(),
                'value' => $value
            ];
        }

        return $entityAttributes;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @param string $field
     * @return string|int|null
     */
    protected function getOrderValue(OrderInterface $order, string $field)
    {
        return $order->getData($field);
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param string $field
     * @return string|int|null
     */
    protected function getProductValue(
        ProductInterface $product,
        string $field,
        ?ProductInterface $childProduct = null
    ) {
        $value = null;

        $attribute = $product->getCustomAttribute($field);

        if ($attribute) {
            $value = $attribute->getValue();
        } elseif ($childProduct) {
            $attribute = $childProduct->getCustomAttribute($field);

            if ($attribute) {
                $value = $attribute->getValue();
            } else {
                $value = $childProduct->getData($field);
            }
        } else {
            $value = $product->getData($field);
        }

        if (null !== $value) {
            try {
                $attribute = $this->attributeRepository->get(\Magento\Catalog\Model\Product::ENTITY, $field);

                if (in_array($attribute->getFrontendInput(), ['select', 'multiselect'])) {
                    $options = $attribute->getOptions();
                    $resultValue = $this->getProductSelectValue($attribute, $value, $options);

                    if (null !== $resultValue) {
                        return $resultValue;
                    }
                }
            } catch (NoSuchEntityException $ex) {
                return $value;
            }
        }

        return $value;
    }

    protected function getProductSelectValue(AttributeInterface $attribute, $value, ?array $options)
    {
        $resultValue = null;

        if ($options) {
            if ('select' === $attribute->getFrontendInput()) {
                foreach ($options as $option) {
                    if ($option->getValue() === $value) {
                        $resultValue = $option->getLabel();
                    }
                }
            } else {
                $values = [];

                foreach (explode(',', $value) as $optionValue) {
                    foreach ($options as $option) {
                        if ($option->getValue() === $optionValue) {
                            $values[] = $option->getLabel();
                        }
                    }
                }

                $resultValue = implode(', ', $values);
            }
        }

        return $resultValue;
    }

    /**
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string $field
     * @return string|int|null
     */
    protected function getCustomerValue(CustomerInterface $customer, string $field)
    {
        $customerArr = $customer->__toArray();

        if (isset($customerArr[$field])) {
            return $customerArr[$field];
        } elseif (isset($customerArr['custom_attributes']) && isset($customerArr['custom_attributes'][$field])) {
            return $customerArr['custom_attributes'][$field]['value'];
        }

        return null;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return string
     */
    protected function getOrderStatus(OrderInterface $order)
    {
        $status = $this->orderStatusMappingRepository->getByMagentoStatus(
            $order->getStatus(),
            (int) $order->getStoreId()
        );

        if ($status) {
            return $status->getEcdpOrderStatus();
        }

        return '';
    }
}
