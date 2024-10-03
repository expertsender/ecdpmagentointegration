<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Ui\Component\ConsentFormElement\Form\Column\Form;

use Endora\ExpertSenderCdp\Model\Consent\FormElement\Form;
use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Endora\ExpertSenderCdp\Model\Consent\FormElement\Form
     */
    protected $form;

    /**
     * @param \Endora\ExpertSenderCdp\Model\Consent\FormElement\Form $form
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
