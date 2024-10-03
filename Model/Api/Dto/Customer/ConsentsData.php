<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\Api\Dto\Customer;

class ConsentsData
{
    /**
     * @var \Endora\ExpertSenderCdp\Model\Api\Dto\Customer\Consent[]
     */
    protected $consents = [];

    /**
     * @var int
     */
    protected $confirmationMessageId;

    /**
     * @return \Endora\ExpertSenderCdp\Model\Api\Dto\Customer\Consent[]
     */
    public function getConsents()
    {
        return $this->consents;
    }

    /**
     * @param array $consents
     * @return self
     */
    public function setConsents(array $consents)
    {
        $this->consents = $consents;

        return $this;
    }

    /**
     * @return bool
     */
    public function getForce()
    {
        return false;
    }

    /**
     * @return int
     */
    public function getConfirmationMessageId()
    {
        return $this->confirmationMessageId;
    }

    /**
     * @param int $confirmationMessageId
     * @return self
     */
    public function setConfirmationMessageId(int $confirmationMessageId)
    {
        $this->confirmationMessageId = $confirmationMessageId;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $consents = [];

        foreach ($this->getConsents() as $consent) {
            $consents[] = $consent->toArray();
        }

        return [
            'consents' => $consents,
            'force' => $this->getForce(),
            'confirmationMessageId' => $this->getConfirmationMessageId()
        ];
    }
}
