<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Ui\Component\ConsentFormElement\Listing\Column;

use Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Form extends Column
{
    /**
     * @var \Endora\ExpertSenderCdp\Model\Consent\FormElement\Form
     */
    protected $form;

    /**
     * @param \Endora\ExpertSenderCdp\Model\Consent\FormElement\Form $form
     */
    public function __construct(\Endora\ExpertSenderCdp\Model\Consent\FormElement\Form $form)
    {
        $this->form = $form;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if ($item[ConsentFormElementInterface::FORM]) {
                    $item[ConsentFormElementInterface::FORM] = $this->form->getLabel($item['form']);
                }
            }
        }

        return $dataSource;
    }
}
