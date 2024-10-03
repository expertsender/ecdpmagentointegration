<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Block\Adminhtml;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class GenericAddButton implements ButtonProviderInterface
{
    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $context;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label' => __('Add New'),
            'class' => 'primary',
            'on_click' => sprintf("location.href = '%s';", $this->getUrl()),
            'sort_order' => 30
        ];
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return $this->context->getUrlBuilder()->getUrl(
            '*/*/edit',
            ['store' => $this->context->getStoreManager()->getDefaultStoreView()->getId()]
        );
    }
}
