<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Api\Data;

interface ConsentFormElementInterface
{
    public const ID = 'id';
    public const CONTENT = 'content';
    public const FORM = 'form';
    public const CONSENT_IDS = 'consent_ids';
    public const STORE = 'store';
    public const ENABLED = 'enabled';
    public const SORT_ORDER = 'sort_order';

    public const INPUT_NAME = 'expertsender_consents';

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
    public function getContent();

    /**
     * @param string $content
     * @return self
     */
    public function setContent(string $content);

    /**
     * @return string
     */
    public function getForm();

    /**
     * @param string $form
     * @return self
     */
    public function setForm(string $form);

    /**
     * @return array
     */
    public function getConsentIds();

    /**
     * @param array $consentIds
     * @return self
     */
    public function setConsentIds(array $consentIds);

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
     * @return bool
     */
    public function getEnabled();

    /**
     * @param bool $enabled
     * @return self
     */
    public function setEnabled(bool $enabled);

    /**
     * @return int|null
     */
    public function getSortOrder();

    /**
     * @param int|null $sortOrder
     * @return self
     */
    public function setSortOrder(?int $sortOrder);
}
