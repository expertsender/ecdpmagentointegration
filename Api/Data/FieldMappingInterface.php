<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Api\Data;

interface FieldMappingInterface
{
    public const ID = 'id';
    public const MAGENTO_FIELD = 'magento_field';
    public const ECDP_FIELD = 'ecdp_field';
    public const ENTITY = 'entity';
    public const STORE = 'store';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return self
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getMagentoField();

    /**
     * @param string $magentoField
     * @return self
     */
    public function setMagentoField(string $magentoField);

    /**
     * @return string
     */
    public function getEcdpField();

    /**
     * @param string $ecdpField
     * @return self
     */
    public function setEcdpField(string $ecdpField);

    /**
     * @return int
     */
    public function getEntity();

    /**
     * @param int $entity
     * @return self
     */
    public function setEntity(int $entity);

    /**
     * @return int
     */
    public function getStore();

    /**
     * @param int $store
     * @return self
     */
    public function setStore(int $store);
}
