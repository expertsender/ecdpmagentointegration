<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Ui\Component\ConsentFormElement\Listing\Column\Form;

use ExpertSender\Ecdp\Model\Consent\FormElement\Form;
use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \ExpertSender\Ecdp\Model\Consent\FormElement\Form
     */
    protected $form;

    /**
     * @param \ExpertSender\Ecdp\Model\Consent\FormElement\Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            $this->options = $this->form->toOptionArray();
        }

        return $this->options;
    }
}
