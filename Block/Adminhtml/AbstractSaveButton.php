<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Block\Adminhtml;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

abstract class AbstractSaveButton implements ButtonProviderInterface
{
    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $context;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(Context $context, RequestInterface $request)
    {
        $this->context = $context;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'button' => [
                        'event' => 'save'
                    ],
                    'Magento_Ui/js/form/button-adapter' => [
                        'actions' => [
                            [
                                'targetName' => $this->getButtonTarget(),
                                'actionName' => 'save',
                                'params' => [
                                    true,
                                    ['store' => $this->request->getParam('store')]
                                ]
                            ]
                        ]
                    ]
                ],
                'form-role' => 'save'
            ],
            'sort_order' => 30
        ];
    }

    /**
     * @return string
     */
    abstract protected function getButtonTarget();
}
