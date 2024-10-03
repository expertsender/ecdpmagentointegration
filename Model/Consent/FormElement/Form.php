<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\Consent\FormElement;

class Form
{
    public const REGISTRATION_FORM = 'registration';
    public const PROFILE_EDIT_FORM = 'profile_edit';
    public const CHECKOUT_FORM = 'checkout';
    public const NEWSLETTER_FORM = 'newsletter';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::REGISTRATION_FORM,
                'label' => self::getLabel(self::REGISTRATION_FORM)
            ],
            [
                'value' => self::PROFILE_EDIT_FORM,
                'label' => self::getLabel(self::PROFILE_EDIT_FORM)
            ],
            [
                'value' => self::CHECKOUT_FORM,
                'label' => self::getLabel(self::CHECKOUT_FORM)
            ],
            [
                'value' => self::NEWSLETTER_FORM,
                'label' => self::getLabel(self::NEWSLETTER_FORM)
            ]
        ];
    }

    /**
     * @param string $value
     * @return \Magento\Framework\Phrase
     */
    public function getLabel(string $value)
    {
        if (self::REGISTRATION_FORM === $value) {
            return __('Registration');
        } elseif (self::PROFILE_EDIT_FORM === $value) {
            return __('Profile Edit');
        } elseif (self::CHECKOUT_FORM === $value) {
            return __('Checkout');
        } elseif (self::NEWSLETTER_FORM === $value) {
            return __('Newsletter');
        }

        return '';
    }
}
