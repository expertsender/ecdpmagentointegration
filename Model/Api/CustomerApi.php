<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Api;

use ExpertSender\Ecdp\Model\Api\Dto\Customer;
use ExpertSender\Ecdp\Model\Api\EcdpApi;

class CustomerApi extends EcdpApi
{
    protected const URL = 'customers';
    protected const GET_BY_EMAIL_URL = '/email';

    protected const MODE_ADD = 'Add';
    protected const MODE_ADD_OR_REPLACE = 'AddOrUpdate';
    protected const MODE_REPLACE = 'Update';

    /**
     * @param \ExpertSender\Ecdp\Model\Api\Dto\Customer $customer
     * @return \ExpertSender\Ecdp\Model\Api\ApiResponse
     */
    public function addCustomer(Customer $customer)
    {
        return $this->postCustomer($customer, self::MODE_ADD);
    }

    /**
     * @param \ExpertSender\Ecdp\Model\Api\Dto\Customer $customer
     * @return \ExpertSender\Ecdp\Model\Api\ApiResponse
     */
    public function updateCustomer(Customer $customer)
    {
        return $this->postCustomer($customer, self::MODE_ADD_OR_REPLACE);
    }

    /**
     * @param string $email
     * @param int $storeId
     * @return \ExpertSender\Ecdp\Model\Api\ApiResponse
     */
    public function getCustomer(string $email, int $storeId)
    {
        return $this->httpClient->makeGet(
            $this->getUrl(self::URL . self::GET_BY_EMAIL_URL . '/' . urlencode($email)),
            $storeId
        );
    }

    /**
     * @param \ExpertSender\Ecdp\Model\Api\Dto\Customer $customer
     * @param string $mode
     * @return \ExpertSender\Ecdp\Model\Api\ApiResponse
     */
    protected function postCustomer(Customer $customer, string $mode)
    {
        $data = [
            'data' => [
                [
                    'email' => $customer->getEmail(),
                    'phone' => $customer->getPhone(),
                    'crmId' => $customer->getCrmId(),
                    'firstName' => $customer->getFirstName(),
                    'lastName' => $customer->getLastName(),
                    'dateOfBirth' => $customer->getDateOfBirth(),
                    'gender' => $customer->getGender(),
                    'customAttributes' => $customer->getCustomAttributes(),
                    'consentsData' => $customer->getConsentsDataArray()
                ]
            ],
            'mode' => $mode,
            'matchBy' => 'Email'
        ];

        return $this->httpClient->makePost(
            $this->getUrl(self::URL),
            $data,
            $customer->getMagentoStoreId()
        );
    }
}
