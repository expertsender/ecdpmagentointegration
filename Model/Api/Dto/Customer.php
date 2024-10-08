<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Api\Dto;

use ExpertSender\Ecdp\Model\Api\Dto\Customer\ConsentsData;

class Customer
{
    public const GENDER_MALE = 'Male';
    public const GENDER_FEMALE = 'Female';
    public const GENDER_NOT_SPECIFIED = 'NotSpecified';

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $phone;

    /**
     * @var string
     */
    protected $crmId;

    /**
     * @var string|null
     */
    protected $firstName;

    /**
     * @var string|null
     */
    protected $lastName;

    /**
     * @var string|null
     */
    protected $dateOfBirth;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var array
     */
    protected $customAttributes;

    /**
     * @var \ExpertSender\Ecdp\Model\Api\Dto\Customer\ConsentsData
     */
    protected $consentsData;

    /**
     * @var int
     */
    protected $magentoStoreId;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return self
     */
    public function setPhone(?string $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getCrmId()
    {
        return $this->crmId;
    }

    /**
     * @param string $crmId
     * @return self
     */
    public function setCrmId(string $crmId)
    {
        $this->crmId = $crmId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     * @return self
     */
    public function setFirstName(?string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     * @return self
     */
    public function setLastName(?string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param string|null $dateOfBirth
     * @return self
     */
    public function setDateOfBirth(?string $dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string|int|null $gender
     * @return self
     */
    public function setGender($gender)
    {
        if (is_int($gender)) {
            switch ($gender) {
                case 1:
                    $gender = self::GENDER_MALE;
                    break;
                case 2:
                    $gender = self::GENDER_FEMALE;
                    break;
                case 3:
                default:
                    $gender = self::GENDER_NOT_SPECIFIED;
                    break;
            }
        }

        if (null === $gender) {
            $gender = self::GENDER_NOT_SPECIFIED;
        }

        $this->gender = $gender;

        return $this;
    }

    /**
     * @return array
     */
    public function getCustomAttributes()
    {
        return $this->customAttributes;
    }

    /**
     * @param array $customAttributes
     * @return self
     */
    public function setCustomAttributes(array $customAttributes)
    {
        $this->customAttributes = $customAttributes;

        return $this;
    }

    /**
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Customer\ConsentsData
     */
    public function getConsentsData()
    {
        return $this->consentsData;
    }

    /**
     * @param \ExpertSender\Ecdp\Model\Api\Dto\Customer\ConsentsData $consentsData
     * @return self
     */
    public function setConsentsData(ConsentsData $consentsData)
    {
        $this->consentsData = $consentsData;

        return $this;
    }

    /**
     * @return array
     */
    public function getConsentsDataArray()
    {
        return $this->consentsData->toArray();
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
