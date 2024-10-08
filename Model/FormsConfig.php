<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class FormsConfig
{
    public const MAIN_PATH = 'endora_expertsender_forms/';
    public const TEXT_BEFORE_CONSENTS = 'general/text_before_consents';
    public const USE_CUSTOM_NEWSLETTER_FORM = 'general/use_custom_newsletter_form';
    public const REGISTRATION_FORM_TYPE = 'form_type/registration_form_type';
    public const REGISTRATION_FORM_MESSAGE_ID = 'form_type/registration_form_message_id';
    public const PROFILE_EDIT_FORM_TYPE = 'form_type/profile_edit_form_type';
    public const PROFILE_EDIT_FORM_MESSAGE_ID = 'form_type/profile_edit_form_message_id';
    public const CHECKOUT_FORM_TYPE = 'form_type/checkout_form_type';
    public const CHECKOUT_FORM_MESSAGE_ID = 'form_type/checkout_form_message_id';
    public const NEWSLETTER_FORM_TYPE = 'form_type/newsletter_form_type';
    public const NEWSLETTER_FORM_MESSAGE_ID = 'form_type/newsletter_form_message_id';

    public const FORM_TYPE_SINGLE_OPT_IN = 'single-opt-in';
    public const FORM_TYPE_DOUBLE_OPT_IN = 'double-opt-in';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param string|null $scope
     * @return string
     */
    public function getTextBeforeConsents(?string $scope = null)
    {
        return (string) $this->getConfig(self::TEXT_BEFORE_CONSENTS);
    }

    /**
     * @param string|null $scope
     * @return bool
     */
    public function useCustomNewsletterForm(?string $scope = null)
    {
        return $this->getConfig(self::USE_CUSTOM_NEWSLETTER_FORM, $scope);
    }

    /**
     * @param string|null $scope
     * @return string|null
     */
    public function getRegistrationFormType(?string $scope = null)
    {
        return $this->getConfig(self::REGISTRATION_FORM_TYPE, $scope);
    }

    /**
     * @param string|null $scope
     * @return int|null
     */
    public function getRegistrationFormMessageId(?string $scope = null)
    {
        return $this->getConfig(self::REGISTRATION_FORM_MESSAGE_ID, $scope);
    }

    /**
     * @param string|null $scope
     * @return string|null
     */
    public function getProfileEditFormType(?string $scope = null)
    {
        return $this->getConfig(self::PROFILE_EDIT_FORM_TYPE, $scope);
    }

    /**
     * @param string|null $scope
     * @return int|null
     */
    public function getProfileEditFormMessageId(?string $scope = null)
    {
        return $this->getConfig(self::PROFILE_EDIT_FORM_MESSAGE_ID, $scope);
    }

    /**
     * @param string|null $scope
     * @return string|null
     */
    public function getCheckoutFormType(?string $scope = null)
    {
        return $this->getConfig(self::CHECKOUT_FORM_TYPE, $scope);
    }

    /**
     * @param string|null $scope
     * @return int|null
     */
    public function getCheckoutFormMessageId(?string $scope = null)
    {
        return $this->getConfig(self::CHECKOUT_FORM_MESSAGE_ID, $scope);
    }

    /**
     * @param string|null $scope
     * @return string|null
     */
    public function getNewsletterFormType(?string $scope = null)
    {
        return $this->getConfig(self::NEWSLETTER_FORM_TYPE, $scope);
    }

    /**
     * @param string|null $scope
     * @return int|null
     */
    public function getNewsletterFormMessageId(?string $scope = null)
    {
        $messageId = $this->getConfig(self::NEWSLETTER_FORM_MESSAGE_ID, $scope);

        return null !== $messageId ? (int) $messageId : null;
    }

    /**
     * @param string $key
     * @param string|null $scope
     * @return mixed
     */
    protected function getConfig(string $key, ?string $scope = null)
    {
        return $this->scopeConfig->getValue(
            self::MAIN_PATH . $key,
            ScopeInterface::SCOPE_STORE,
            $scope
        );
    }
}
