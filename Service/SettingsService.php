<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Service;

use ExpertSender\Ecdp\Exception\EESException;
use ExpertSender\Ecdp\Model\Api\ApiResponse;
use ExpertSender\Ecdp\Model\Api\Dto\Settings\Attribute;
use ExpertSender\Ecdp\Model\Api\Dto\Settings\Consent;
use ExpertSender\Ecdp\Model\Api\SettingsApi;
use Magento\Framework\Serialize\SerializerInterface;

class SettingsService
{
    /**
     * @var \ExpertSender\Ecdp\Model\Api\SettingsApi
     */
    protected $settingsApi;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    /**
     * @var array
     */
    protected $consents;

    /**
     * @var array
     */
    protected $customerAttributes;

    /**
     * @var array
     */
    protected $productAttributes;

    /**
     * @var array
     */
    protected $orderAttributes;

    /**
     * @param \ExpertSender\Ecdp\Model\Api\SettingsApi $settingsApi
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(SettingsApi $settingsApi, SerializerInterface $serializer)
    {
        $this->settingsApi = $settingsApi;
        $this->serializer = $serializer;
    }

    /**
     * @param int $storeId
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Settings\Consent[]
     */
    public function getCustomerConsents(int $storeId)
    {
        if (!isset($this->consents)) {
            $response = $this->settingsApi->getCustomerConsents($storeId);

            if (SettingsApi::HTTP_SUCCESS !== $response->getResponseCode()) {
                throw new EESException(__('Customer consents fetch error | ' . $response->getData()));
            }

            $this->consents = $this->getConsentsFromResponse($response);
        }
        
        return $this->consents;
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getCustomerConsentsOptions(int $storeId)
    {
        $options = [];

        foreach ($this->getCustomerConsents($storeId) as $consent) {
            $options[] = [
                'value' => $consent->getId(),
                'label' => $consent->getName()
            ];
        }

        return $options;
    }

    /**
     * @param int $storeId
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Settings\Attribute[]
     * @throws \ExpertSender\Ecdp\Exception\EESException
     */
    public function getCustomerAttributes(int $storeId)
    {
        if (!isset($this->customerAttributes)) {
            $response = $this->settingsApi->getCustomerAttributes($storeId);

            if (SettingsApi::HTTP_SUCCESS !== $response->getResponseCode()) {
                throw new EESException(__('Customer attributes fetch error | ' . $response->getData()));
            }

            $this->customerAttributes = $this->getAttributesFromResponse($response);
        }
        
        return $this->customerAttributes;
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getCustomerAttributesOptions(int $storeId)
    {
        return $this->getAttributesOptions($this->getCustomerAttributes($storeId));
    }

    /**
     * @param int $storeId
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Settings\Attribute[]
     * @throws \ExpertSender\Ecdp\Exception\EESException
     */
    public function getProductAttributes(int $storeId)
    {
        if (!isset($this->productAttributes)) {
            $response = $this->settingsApi->getProductAttributes($storeId);

            if (SettingsApi::HTTP_SUCCESS !== $response->getResponseCode()) {
                throw new EESException(__('Product attributes fetch error | ' . $response->getData()));
            }

            $this->productAttributes = $this->getAttributesFromResponse($response);
        }

        return $this->productAttributes;
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getProductAttributesOptions(int $storeId)
    {
        return $this->getAttributesOptions($this->getProductAttributes($storeId));
    }

    /**
     * @param int $storeId
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Settings\Attribute[]
     * @throws \ExpertSender\Ecdp\Exception\EESException
     */
    public function getOrderAttributes(int $storeId)
    {
        if (!isset($this->orderAttributes)) {
            $response = $this->settingsApi->getOrderAttributes($storeId);

            if (SettingsApi::HTTP_SUCCESS !== $response->getResponseCode()) {
                throw new EESException(__('Order attributes fetch error | ' . $response->getData()));
            }

            $this->orderAttributes = $this->getAttributesFromResponse($response);
        }
        
        return $this->orderAttributes;
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getOrderAttributesOptions(int $storeId)
    {
        return $this->getAttributesOptions($this->getOrderAttributes($storeId));
    }

    /**
     * @param \ExpertSender\Ecdp\Model\Api\ApiResponse $response
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Settings\Consent[]
     */
    protected function getConsentsFromResponse(ApiResponse $response)
    {
        $data = $this->serializer->unserialize($response->getData());
        $consents = [];

        foreach ($data['data'] as $consent) {
            $consentDto = new Consent();
            $consentDto->setId($consent['id'])
                ->setName($consent['name'])
                ->setType($consent['type']);

            $consents[] = $consentDto;
        }

        return $consents;
    }

    /**
     * @param \ExpertSender\Ecdp\Model\Api\ApiResponse $response
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Settings\Attribute[]
     */
    protected function getAttributesFromResponse(ApiResponse $response)
    {
        $data = $this->serializer->unserialize($response->getData());
        $attributes = [];

        foreach ($data['data'] as $attribute) {
            $attributeDto = new Attribute($attribute['id'], $attribute['name']);
            $attributes[] = $attributeDto;
        }

        return $attributes;
    }

    /**
     * @param \ExpertSender\Ecdp\Model\Api\Dto\Settings\Attribute[] $attributes
     * @return array
     */
    protected function getAttributesOptions(array $attributes)
    {
        $options = [];

        foreach ($attributes as $attribute) {
            $options[] = [
                'value' => $attribute->getName(),
                'label' => $attribute->getName()
            ];
        }

        return $options;
    }
}
