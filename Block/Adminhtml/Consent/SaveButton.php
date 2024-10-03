<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Block\Adminhtml\Consent;

use Endora\ExpertSenderCdp\Block\Adminhtml\AbstractSaveButton;

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
