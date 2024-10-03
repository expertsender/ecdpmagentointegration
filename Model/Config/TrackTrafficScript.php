<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model\Config;

use Magento\Framework\App\Config\Value;

class TrackTrafficScript extends Value
{
    /**
     * {@inheritdoc}
     */
    public function afterSave()
    {
        if ($this->isValueChanged()) {
            $this->cacheTypeList->cleanType(\Magento\PageCache\Model\Cache\Type::TYPE_IDENTIFIER);
        }

        return parent::afterSave();
    }
}
