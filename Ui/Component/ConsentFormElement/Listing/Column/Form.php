<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Ui\Component\ConsentFormElement\Listing\Column;

use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Form extends Column
{
    /**
     * @var \ExpertSender\Ecdp\Model\Consent\FormElement\Form
     */
    protected $form;

    /**
     * @param \ExpertSender\Ecdp\Model\Consent\FormElement\Form $form
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \ExpertSender\Ecdp\Model\Consent\FormElement\Form $form,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components,
        array $data
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
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
