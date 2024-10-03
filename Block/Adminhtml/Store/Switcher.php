<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Block\Adminhtml\Store;

class Switcher extends \Magento\Backend\Block\Store\Switcher
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_hasDefaultOption = false;
    }

    /**
     * {@inheritdoc}
     */
    public function isStoreSwitchEnabled()
    {
        return (bool) (parent::isStoreSwitchEnabled() && null === $this->_request->getParam('id'));
    }
}