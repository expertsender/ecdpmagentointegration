<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Block\Adminhtml\OrderStatusMapping;

use ExpertSender\Ecdp\Block\Adminhtml\AbstractSaveButton;

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
