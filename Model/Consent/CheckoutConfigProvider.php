<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Consent;

use ExpertSender\Ecdp\Helper\ConsentFormElementHelper;
use ExpertSender\Ecdp\Model\FormsConfig;
use ExpertSender\Ecdp\Model\Consent\FormElement\Form;
use Magento\Checkout\Model\ConfigProviderInterface;

class CheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \ExpertSender\Ecdp\Helper\ConsentFormElementHelper
     */
    protected $helper;

    /**
     * @var \ExpertSender\Ecdp\Model\FormsConfig
     */
    protected $formsConfig;

    /**
     * @param \ExpertSender\Ecdp\Helper\ConsentFormElementHelper $helper
     * @param \ExpertSender\Ecdp\Model\FormsConfig $formsConfig
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
