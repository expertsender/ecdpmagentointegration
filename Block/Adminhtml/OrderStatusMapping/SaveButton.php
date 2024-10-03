<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Block\Adminhtml\OrderStatusMapping;

use Endora\ExpertSenderCdp\Block\Adminhtml\AbstractSaveButton;

class SaveButton extends AbstractSaveButton
{
    /**
     * {@inheritdoc}
     */
    protected function getButtonTarget()
    {
        return 'order_status_mapping_form.order_status_mapping_form';
    }
}
