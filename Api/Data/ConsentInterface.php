<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Api\Data;

interface ConsentInterface
{
    public const ID = 'id';
    public const NAME = 'name';
    public const ECDP_ID = 'ecdp_id';
    public const STORE = 'store';
    public const ECDP_LABEL = 'ecdp_label';

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
    public function getName();

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name);

    /**
     * @return int
     */
    public function getEcdpId();

    /**
     * @param int $ecdpId
     * @return self
     */
    public function setEcdpId(int $ecdpId);

    /**
     * @return int
     */
    public function getStore();

    /**
     * @param int $store
     * @return self
     */
    public function setStore(int $store);

    /**
     * @return string
     */
    public function getEcdpLabel();

    /**
     * @param string $ecdpLabel
     * @return self
     */
    public function setEcdpLabel(string $ecdpLabel);
}
