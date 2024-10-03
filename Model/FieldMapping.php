<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model;

use Endora\ExpertSenderCdp\Api\Data\FieldMappingInterface;
use Magento\Framework\Model\AbstractModel;

class FieldMapping extends AbstractModel implements FieldMappingInterface
{
    protected $_eventPrefix = 'endora_expertsender_field_mapping';
    protected $_eventObject = 'field_mapping';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\Endora\ExpertSenderCdp\Model\ResourceModel\FieldMapping::class);
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
    public function getMagentoField()
    {
        return $this->getData(self::MAGENTO_FIELD);
    }

    /**
     * {@inheritdoc}
     */
    public function setMagentoField(string $magentoField)
    {
        return $this->setData(self::MAGENTO_FIELD, $magentoField);
    }

    /**
     * {@inheritdoc}
     */
    public function getEcdpField()
    {
        return $this->getData(self::ECDP_FIELD);
    }

    /**
     * {@inheritdoc}
     */
    public function setEcdpField(string $ecdpField)
    {
        return $this->setData(self::ECDP_FIELD, $ecdpField);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return $this->getData(self::ENTITY);
    }

    /**
     * {@inheritdoc}
     */
    public function setEntity(int $entity)
    {
        return $this->setData(self::ENTITY, $entity);
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
}
