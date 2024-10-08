<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Consent;

use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use Magento\Framework\Model\AbstractModel;

class FormElement extends AbstractModel implements ConsentFormElementInterface
{
    protected $_eventPrefix = 'endora_expertsender_consent_form_element';
    protected $_eventObject = 'form_element';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\ExpertSender\Ecdp\Model\ResourceModel\Consent\FormElement::class);
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
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setContent(string $content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        return $this->getData(self::FORM);
    }

    /**
     * {@inheritdoc}
     */
    public function setForm(string $form)
    {
        return $this->setData(self::FORM, $form);
    }

    /**
     * {@inheritdoc}
     */
    public function getConsentIds()
    {
        $consentIds = $this->getData(self::CONSENT_IDS);

        if (null === $consentIds) {
            return [];
        }

        return explode(',', $consentIds);
    }

    /**
     * {@inheritdoc}
     */
    public function setConsentIds(?array $consentIds)
    {
        if (null === $consentIds || empty($consentIds)) {
            return $this->setData(self::CONSENT_IDS, $consentIds);
        }

        return $this->setData(self::CONSENT_IDS, implode(',', $consentIds));
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
    public function getEnabled()
    {
        return $this->getData(self::ENABLED);
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled(bool $enabled)
    {
        return $this->setData(self::ENABLED, $enabled);
    }

    /**
     * {@inheritdoc}
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * {@inheritdoc}
     */
    public function setSortOrder(?int $sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * {@inheritdoc}
     */
    public function setData($key, $value = null)
    {
        parent::setData($key, $value);

        if (isset($this->_data[self::CONSENT_IDS]) && is_array($this->_data[self::CONSENT_IDS])) {
            $this->_data[self::CONSENT_IDS] = implode(',', $this->_data[self::CONSENT_IDS]);
        }

        return $this;
    }
}
