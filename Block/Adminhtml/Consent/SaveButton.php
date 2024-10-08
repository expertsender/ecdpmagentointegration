<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Block\Adminhtml\Consent;

use ExpertSender\Ecdp\Block\Adminhtml\AbstractSaveButton;

class SaveButton extends AbstractSaveButton
{
    /**
     * {@inheritdoc}
     */
    protected function getButtonTarget()
    {
        return 'consent_form.consent_form';
    }
}
