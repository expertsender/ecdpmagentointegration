<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Block\Adminhtml\FieldMapping;

use Endora\ExpertSenderCdp\Block\Adminhtml\AbstractSaveButton;

class SaveButton extends AbstractSaveButton
{
    /**
     * {@inheritdoc}
     */
    protected function getButtonTarget()
    {
        return 'field_mapping_form.field_mapping_form';
    }
}
