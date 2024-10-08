<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Block\Adminhtml;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class GenericDeleteButton implements ButtonProviderInterface
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
        $data = [];

        if ($this->getItemId()) {
            $data = [
                'label' => __('Delete'),
                'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this item?')
                    . '\', \'' . $this->getDeleteUrl() . '\')',
                'class' => 'delete',
                'sort_order' => 20
            ];
        }

        return $data;
    }

    /**
     * @return string
     */
    protected function getDeleteUrl()
    {
        return $this->context->getUrlBuilder()
            ->getUrl(
                '*/*/delete',
                ['id' => $this->getItemId()]
            );
    }

    /**
     * @return int
     */
    protected function getItemId()
    {
        return $this->context->getRequest()->getParam('id');
    }
}
