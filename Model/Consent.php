<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model;

use ExpertSender\Ecdp\Api\Data\ConsentInterface;
use Magento\Framework\Model\AbstractModel;

class Consent extends AbstractModel implements ConsentInterface
{
    protected $_eventPrefix = 'endora_expertsender_consent';
    protected $_eventObject = 'consent';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\ExpertSender\Ecdp\Model\ResourceModel\Consent::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getEcdpId()
    {
        return $this->getData(self::ECDP_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setEcdpId(int $ecdpId)
    {
        return $this->setData(self::ECDP_ID, $ecdpId);
    }

    /**
     * {@inheritdoc}
     */
    public function getStore()
    {
        return $this->getData(self::STORE);
    }

    /**
     * {@inheritdoc}
     */
    public function setStore(int $store)
    {
        return $this->setData(self::STORE, $store);
    }

    /**
     * {@inheritdoc}
     */
    public function getEcdpLabel()
    {
        return $this->getData(self::ECDP_LABEL);
    }

    /**
     * {@inheritdoc}
     */
    public function setEcdpLabel(string $ecdpLabel)
    {
        return $this->setData(self::ECDP_LABEL, $ecdpLabel);
    }
}
