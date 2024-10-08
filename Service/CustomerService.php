<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Service;

use ExpertSender\Ecdp\Exception\EESException;
use ExpertSender\Ecdp\Model\Api\ApiResponse;
use ExpertSender\Ecdp\Model\Api\CustomerApi;
use ExpertSender\Ecdp\Model\Api\Dto\Customer;
use ExpertSender\Ecdp\Model\Api\Dto\Customer\Consent;
use ExpertSender\Ecdp\Model\Api\Dto\Customer\ConsentsData;
use ExpertSender\Ecdp\Service\DataConverter;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;

class CustomerService
{
    /**
     * @var \ExpertSender\Ecdp\Model\Api\CustomerApi
     */
    protected $customerApi;

    /**
     * @var \ExpertSender\Ecdp\Service\DataConverter
     */
    protected $dataConverter;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    /**
     * @param \ExpertSender\Ecdp\Model\Api\CustomerApi $customerApi
     * @param \ExpertSender\Ecdp\Service\DataConverter $dataConverter
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(
        CustomerApi $customerApi,
        DataConverter $dataConverter,
        CustomerRepositoryInterface $customerRepository,
        SerializerInterface $serializer
    ) {
        $this->customerApi = $customerApi;
        $this->dataConverter = $dataConverter;
        $this->customerRepository = $customerRepository;
        $this->serializer = $serializer;
    }

    /**
     * @param int $customerId
     * @param array|null $customData
     * @return bool
     * @throws \ExpertSender\Ecdp\Exception\EESException
     */
    public function sendNewCustomer(
        int $customerId,
        ?array $customData = null
    ) {
        $response = $this->customerApi->addCustomer(
            $this->getCustomerDto($customerId, $customData)
        );

        if (CustomerApi::HTTP_CREATED !== $response->getResponseCode()) {
            throw new EESException(__('Customer synchronization error | ' . $response->getData()));
        }

        return true;
    }

    /**
     * @param int $customerId
     * @param array|null $customData
     * @return bool
     * @throws \ExpertSender\Ecdp\Exception\EESException
     */
    public function sendCustomerUpdate(
        int $customerId,
        ?array $customData = null
    ) {
        $response = $this->customerApi->updateCustomer(
            $this->getCustomerDto($customerId, $customData)
        );

        if (CustomerApi::HTTP_CREATED !== $response->getResponseCode()) {
            throw new EESException(__('Customer synchronization error | ' . $response->getData()));
        }

        return true;
    }

    /**
     * @param array $customData
     * @param int $storeId
     * @return bool
     * @throws \ExpertSender\Ecdp\Exception\EESException
     */
    public function sendGuestCustomerUpdate(array $customData, int $storeId)
    {
        try {
            $customer = $this->customerRepository->get($customData['email']);
            $customerDto = $this->getCustomerDto($customer->getId(), $customData);
        } catch (NoSuchEntityException $ex) {
            $customerDto = $this->dataConverter->guestCustomerToDto($customData, $storeId);
        }

        $response = $this->customerApi->updateCustomer($customerDto);

        if (CustomerApi::HTTP_CREATED !== $response->getResponseCode()) {
            throw new EESException(__('Guest customer synchronization error | ' . $response->getData()));
        }

        return true;
    }

    /**
     * @param string $email
     * @param int $storeId
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Customer
     * @throws \ExpertSender\Ecdp\Exception\EESException
     */
    public function getCustomer(string $email, int $storeId)
    {
        $response = $this->customerApi->getCustomer($email, $storeId);

        if (CustomerApi::HTTP_SUCCESS !== $response->getResponseCode()) {
            throw new EESException(__('Customer fetch error | ' . $response->getData()));
        }

        return $this->getCustomerFromResponse($response);
    }

    /**
     * @param int $customerId
     * @param array|null $customData
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Customer
     */
    protected function getCustomerDto(
        int $customerId,
        ?array $customData = null
    ) {
        $customer = $this->customerRepository->getById($customerId);
        return $this->dataConverter->customerToDto($customer, $customData);
    }

    /**
     * @param \ExpertSender\Ecdp\Model\Api\ApiResponse $response
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Customer
     */
    protected function getCustomerFromResponse(ApiResponse $response)
    {
        $data = $this->serializer->unserialize($response->getData());
        $data = $data['data'];
        $customerDto = new Customer();
        $customerDto->setEmail($data['email']);
        $consentsData = new ConsentsData();

        if (isset($data['consentsData'])) {
            $consentDtoArr = [];

            foreach ($data['consentsData']['consents'] as $consent) {
                $consentDtoArr[] = new Consent($consent['id'], $consent['value']);
            }

            $consentsData->setConsents($consentDtoArr);
        }

        return $customerDto->setConsentsData($consentsData);
    }
}
