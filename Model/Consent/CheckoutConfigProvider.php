<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\Consent;

use Endora\ExpertSenderCdp\Helper\ConsentFormElementHelper;
use Endora\ExpertSenderCdp\Model\FormsConfig;
use Endora\ExpertSenderCdp\Model\Consent\FormElement\Form;
use Magento\Checkout\Model\ConfigProviderInterface;

class CheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Endora\ExpertSenderCdp\Helper\ConsentFormElementHelper
     */
    protected $helper;

    /**
     * @var \Endora\ExpertSenderCdp\Model\FormsConfig
     */
    protected $formsConfig;

    /**
     * @param \Endora\ExpertSenderCdp\Helper\ConsentFormElementHelper $helper
     * @param \Endora\ExpertSenderCdp\Model\FormsConfig $formsConfig
     */
    public function __construct(ConsentFormElementHelper $helper, FormsConfig $formsConfig)
    {
        $this->helper = $helper;
        $this->formsConfig = $formsConfig;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $formElements = $this->helper->getFormElements(Form::CHECKOUT_FORM);
        $elements = [];

        foreach ($formElements->getItems() as $formElement) {
            $elements[] = [
                'value' => implode('_', $formElement->getConsentIds()),
                'content' => $formElement->getContent()
            ];
        }

        return [
            'expertSenderCustomerConsents' => [
                'formElements' => $elements,
                'textBeforeConsents' => $this->formsConfig->getTextBeforeConsents()
            ]
        ];
    }
}
